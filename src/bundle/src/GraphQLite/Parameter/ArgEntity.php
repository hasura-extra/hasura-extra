<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\GraphQLite\Parameter;

use Doctrine\Persistence\ObjectRepository;
use GraphQL\Language\AST\FieldNode;
use GraphQL\Language\AST\FragmentSpreadNode;
use GraphQL\Language\AST\SelectionSetNode;
use GraphQL\Language\AST\VariableNode;
use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Hasura\GraphQLiteBridge\Parameter\ArgNamingParameterInterface;
use Hasura\GraphQLiteBridge\Parameter\ArgNamingParameterTrait;
use Hasura\GraphQLiteBridge\ScalarType\Uuid;
use TheCodingMachine\Graphqlite\Bundle\Context\SymfonyRequestContextInterface;
use TheCodingMachine\GraphQLite\Exceptions\GraphQLException;
use TheCodingMachine\GraphQLite\GraphQLRuntimeException;

final class ArgEntity implements ArgNamingParameterInterface
{
    use ArgNamingParameterTrait;

    public function __construct(
        private ObjectRepository $repository,
        string $name,
        string $argName,
        private string $fieldName,
        private string $inputType,
        private bool $nullableEntity,
        private bool $isIdentifierField
    ) {
        $this->name = $name;
        $this->argName = $argName;
    }

    public function getType(): InputType
    {
        $type = match (rtrim($this->inputType, '!')) {
            'ID' => Type::id(),
            'Int' => Type::int(),
            'uuid' => Uuid::getInstance(),
            'String' => Type::string(),
            default => throw new GraphQLRuntimeException(sprintf('Only support arg input type: `Int`, `ID`, `uuid` and `String`, given `%s`.', $this->inputType))
        };

        return new NonNull($type);
    }

    public function hasDefaultValue(): bool
    {
        return false;
    }

    public function getDefaultValue()
    {
        return null;
    }

    protected function doResolve(?object $source, array $args, $context, ResolveInfo $info): ?object
    {
        if ($this->isIdentifierField) {
            $this->resolveNPlusOne($info);

            $entity = $this->repository->find($args[$this->name]);
        } else {
            $entity = $this->repository->findOneBy([
                $this->fieldName => $args[$this->name],
            ]);
        }

        if (null === $entity && !$this->nullableEntity) {
            throw new GraphQLException(sprintf('Can not found instance by `%s`', $args[$this->name]), category: 'input_args', extensions: [
                'field' => $this->argName,
            ]);
        }

        /** @var SymfonyRequestContextInterface $context */
        $context->getRequest()->attributes->set(
            sprintf('_raw_%s', $this->name),
            $args[$this->name]
        );

        return $entity;
    }

    private function resolveNPlusOne(ResolveInfo $resolveInfo): void
    {
        static $caches = [];

        $cacheKey = sprintf('%s/%s/%s', spl_object_hash($resolveInfo->operation), $this->fieldName, $this->argName);

        if (!isset($caches[$cacheKey])) {
            $caches[$cacheKey] = true;

            $ids = $this->collectIdsFromSelectionSet(
                $resolveInfo->fieldName,
                $resolveInfo->operation->selectionSet,
                $resolveInfo->fragments,
                $resolveInfo->variableValues
            );

            $this->repository->findBy([
                $this->fieldName => $ids,
            ]);
        }
    }

    private function collectIdsFromSelectionSet(
        string $fieldName,
        SelectionSetNode $setNode,
        array $fragments,
        array $variables
    ): array {
        $ids = [];

        foreach ($setNode->selections as $selection) {
            if ($selection instanceof FieldNode) {
                $ids[] = $this->collectIdFromFieldNode($fieldName, $selection, $variables);
            } elseif ($selection instanceof FragmentSpreadNode) {
                $subSetNode = $fragments[$selection->name->value]->selectionSet;
                $ids = array_merge(
                    $ids,
                    $this->collectIdsFromSelectionSet($fieldName, $subSetNode, $fragments, $variables)
                );
            } else {
                throw new \LogicException('Selection node should be `FieldNode` or `FragmentSpreadNode`');
            }
        }

        return array_filter($ids, fn ($value) => null !== $value);
    }

    private function collectIdFromFieldNode(string $fieldName, FieldNode $node, array $variables): mixed
    {
        if ($node->name->value !== $fieldName) {
            return null;
        }

        foreach ($node->arguments as $argument) {
            if ($argument->name->value !== $this->argName) {
                continue;
            }

            $argumentNode = $argument->value;

            if ($argumentNode instanceof VariableNode) {
                return $variables[$argumentNode->name->value];
            } else {
                return $argumentNode->value;
            }
        }

        throw new \LogicException(sprintf('Not found arg name `%s`', $this->argName));
    }
}
