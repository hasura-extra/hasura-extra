<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Tests\Parameter;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Hasura\GraphQLiteBridge\Parameter\AvoidExplicitDefaultNull;
use Hasura\GraphQLiteBridge\Tests\TestCase;
use TheCodingMachine\GraphQLite\Parameters\InputTypeParameterInterface;

final class AvoidExplicitDefaultNullTest extends TestCase
{
    public function testCanAvoidExplicitDefaultNullScalarType(): void
    {
        $parameter = new AvoidExplicitDefaultNull($this->createScalarInputTypeParameter());

        $this->assertFalse($parameter->hasDefaultValue());
    }

    public function testCanAvoidExplicitDefaultNullInputObjectType(): void
    {
        $parameter = new AvoidExplicitDefaultNull($this->createInputObjectTypeParameter());

        /** @var InputObjectType $type */
        $type = $parameter->getType();
        $this->assertFalse($type->getFields()['id']->defaultValueExists());
    }

    private function createScalarInputTypeParameter(): InputTypeParameterInterface
    {
        return new class() implements InputTypeParameterInterface {
            public function resolve(?object $source, array $args, $context, ResolveInfo $info)
            {
            }

            public function getType(): InputType
            {
                return Type::string();
            }

            public function hasDefaultValue(): bool
            {
                return true;
            }

            public function getDefaultValue()
            {
                return null;
            }
        };
    }

    private function createInputObjectTypeParameter(): InputTypeParameterInterface
    {
        return new class() implements InputTypeParameterInterface {
            public function resolve(?object $source, array $args, $context, ResolveInfo $info)
            {
                
            }

            public function getType(): InputType
            {
                return new InputObjectType(
                    [
                        'name' => 'object',
                        'fields' => [
                            'id' => [
                                'type' => Type::string(),
                                'defaultValue' => null,
                            ],
                        ],
                    ]
                );
            }

            public function hasDefaultValue(): bool
            {
                return false;
            }

            public function getDefaultValue()
            {
                return null;
            }
        };
    }
}
