<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\GraphQLite\Parameter;

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
use Illuminate\Database\Eloquent\Collection;
use TheCodingMachine\GraphQLite\Exceptions\GraphQLException;
use TheCodingMachine\GraphQLite\GraphQLRuntimeException;

final class ArgModel implements ArgNamingParameterInterface
{
    use ArgNamingParameterTrait;

    public function __construct(
        private string $modelClass,
        string $name,
        string $argName,
        private string $fieldName,
        private string $inputType,
        private bool $nullableModel,
    ) {
        $this->name = $name;
        $this->argName = $argName;
    }

    protected function doResolve(?object $source, array $args, $context, ResolveInfo $info)
    {
        $argValue = $args[$this->name];
        $models = $this->getModels($info);
        $model = $models[$argValue] ?? null;

        if (null === $model && !$this->nullableModel) {
            throw new GraphQLException(sprintf('Can not found instance by `%s`', $argValue), category:   'input_args', extensions: ['field' => $this->argName]);
        }

        return $model;
    }

    public function getType(): InputType
    {
        $type = match (rtrim($this->inputType, '!')) {
            'ID' => Type::id(),
            'Int' => Type::int(),
            'uuid' => Uuid::getInstance(),
            'String' => Type::string(),
            default => throw new GraphQLRuntimeException(
                sprintf('Only support arg input type: `Int`, `ID`, `uuid` and `String`, given `%s`.', $this->inputType)
            )
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

    private function getModels(ResolveInfo $resolveInfo): Collection
    {
        // resolve N+1 problems
        static $stack = [];

        $cacheKey = sprintf('%s/%s/%s/%s', spl_object_hash($resolveInfo->operation), $this->modelClass, $resolveInfo->fieldName, $this->argName);

        if (!isset($stack[$cacheKey])) {
            $values = $this->collectInputValuesFromSelectionSet(
                $resolveInfo->fieldName,
                $resolveInfo->operation->selectionSet,
                $resolveInfo->fragments,
                $resolveInfo->variableValues
            );

            $modelClass = $this->modelClass;

            $stack[$cacheKey] = $modelClass::whereIn($this->fieldName, $values)->get()->keyBy($this->fieldName);
        }

        return $stack[$cacheKey];
    }

    private function collectInputValuesFromSelectionSet(
        string $fieldName,
        SelectionSetNode $setNode,
        array $fragments,
        array $variables
    ): array {
        $values = [];

        foreach ($setNode->selections as $selection) {
            if ($selection instanceof FieldNode) {
                $values[] = $this->collectInputValueFromFieldNode($fieldName, $selection, $variables);
            } elseif ($selection instanceof FragmentSpreadNode) {
                $subSetNode = $fragments[$selection->name->value]->selectionSet;
                $values = array_merge(
                    $values,
                    $this->collectInputValuesFromSelectionSet($fieldName, $subSetNode, $fragments, $variables)
                );
            } else {
                throw new \LogicException('Selection node should be `FieldNode` or `FragmentSpreadNode`');
            }
        }

        return array_filter($values, fn($value) => null !== $value);
    }

    private function collectInputValueFromFieldNode(string $fieldName, FieldNode $node, array $variables): mixed
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