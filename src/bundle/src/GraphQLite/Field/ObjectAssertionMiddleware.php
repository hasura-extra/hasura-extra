<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\GraphQLite\Field;

use GraphQL\Type\Definition\FieldDefinition;
use Hasura\Bundle\GraphQLite\Attribute\ObjectAssertion;
use Hasura\Bundle\GraphQLite\ObjectAssertion\Executor;
use Hasura\GraphQLiteBridge\Parameter\ParameterUtils;
use TheCodingMachine\GraphQLite\Middlewares\FieldHandlerInterface;
use TheCodingMachine\GraphQLite\Middlewares\FieldMiddlewareInterface;
use TheCodingMachine\GraphQLite\Parameters\InputTypeParameterInterface;
use TheCodingMachine\GraphQLite\QueryFieldDescriptor;

final class ObjectAssertionMiddleware implements FieldMiddlewareInterface
{
    public function __construct(private Executor $executor)
    {
    }

    public function process(
        QueryFieldDescriptor $queryFieldDescriptor,
        FieldHandlerInterface $fieldHandler
    ): ?FieldDefinition {
        $annotations = $queryFieldDescriptor->getMiddlewareAnnotations();
        /** @var ObjectAssertion[] $attributes */
        $attributes = $annotations->getAnnotationsByType(ObjectAssertion::class);
        $activeAttributes = array_filter(
            $attributes,
            fn (ObjectAssertion $attr) => $attr->getMode() & ObjectAssertion::AFTER_RESOLVE_CALL
        );

        if (empty($activeAttributes)) {
            return $fieldHandler->handle($queryFieldDescriptor);
        }

        $executor = $this->executor;
        $resolver = $queryFieldDescriptor->getResolver();
        $parameters = $queryFieldDescriptor->getParameters();

        $queryFieldDescriptor->setResolver(
            static function (...$args) use ($executor, $resolver, $parameters, $activeAttributes) {
                $result = call_user_func($resolver, ...$args);
                $args = array_combine(array_keys($parameters), $args);

                foreach ($activeAttributes as $attribute) {
                    /**
                     * @var ObjectAssertion $attribute
                     * @var InputTypeParameterInterface $parameter
                     */
                    $object = $args[$attribute->getTarget()];
                    $parameter = $parameters[$attribute->getTarget()];
                    $argNamingParameter = ParameterUtils::getArgNamingParameter($parameter);
                    $atPath = $argNamingParameter ? $argNamingParameter->getArgName() : $attribute->getTarget();

                    $executor->execute(
                        $object,
                        $atPath,
                        $attribute->getCustomViolationPropertyPaths(),
                        $attribute->getGroups()
                    );
                }

                return $result;
            }
        );

        return $fieldHandler->handle($queryFieldDescriptor);
    }
}
