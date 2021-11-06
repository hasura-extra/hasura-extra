<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Parameter;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\WrappingType;
use TheCodingMachine\GraphQLite\Parameters\InputTypeParameterInterface;

/**
 * Help to solve an issue: https://github.com/hasura/graphql-engine/issues/7772
 */
final class AvoidExplicitDefaultNull implements InputTypeParameterInterface, WrappingParameterInterface
{
    use WrappingParameterTrait;

    public function __construct(InputTypeParameterInterface $parameter)
    {
        $this->parameter = $parameter;
    }

    public function resolve(?object $source, array $args, $context, ResolveInfo $info)
    {
        return $this->parameter->resolve($source, $args, $context, $info);
    }

    public function getType(): InputType
    {
        $type = $this->parameter->getType();

        $this->avoidDefaultNull($type);

        return $type;
    }

    public function hasDefaultValue(): bool
    {
        return $this->parameter->hasDefaultValue() && null !== $this->parameter->getDefaultValue();
    }

    public function getDefaultValue()
    {
        return $this->parameter->getDefaultValue();
    }

    private function avoidDefaultNull(InputType $type): void
    {
        if ($type instanceof WrappingType) {
            $type = $type->getWrappedType(true);
        }

        if (!$type instanceof InputObjectType) {
            return;
        }

        $fields = $type->config['fields'] ?? [];
        $fields = is_callable($fields) ? call_user_func($fields) : $fields;

        foreach ($fields as &$fieldConfig) {
            if (array_key_exists('defaultValue', $fieldConfig) && null === $fieldConfig['defaultValue']) {
                unset($fieldConfig['defaultValue']);
            }
        }

        $type->config['fields'] = $fields;

        foreach ($type->getFields() as $field) {
            $this->avoidDefaultNull($field->getType());
        }
    }
}
