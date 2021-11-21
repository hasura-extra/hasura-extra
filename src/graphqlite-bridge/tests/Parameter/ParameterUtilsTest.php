<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Tests\Parameter;

use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\ResolveInfo;
use Hasura\GraphQLiteBridge\Parameter\ArgNamingParameterInterface;
use Hasura\GraphQLiteBridge\Parameter\ParameterUtils;
use Hasura\GraphQLiteBridge\Parameter\WrappingParameterInterface;
use Hasura\GraphQLiteBridge\Tests\TestCase;
use TheCodingMachine\GraphQLite\Parameters\InputTypeParameterInterface;
use TheCodingMachine\GraphQLite\Parameters\ParameterInterface;

final class ParameterUtilsTest extends TestCase
{
    public function testGetArgNaming(): void
    {
        $wrapped = $this->createMock(ArgNamingParameterInterface::class);
        $firstWrapper = $this->createMockWrapper($wrapped);
        $lastWrapper = $this->createMockWrapper($firstWrapper);

        $this->assertSame($wrapped, ParameterUtils::getArgNamingParameter($firstWrapper));
        $this->assertSame($wrapped, ParameterUtils::getArgNamingParameter($lastWrapper));

        $wrapper = $this->createMockWrapper($this->createMock(InputTypeParameterInterface::class));

        $this->assertNull(ParameterUtils::getArgNamingParameter($wrapper));
    }

    private function createMockWrapper(InputTypeParameterInterface $willReturn): InputTypeParameterInterface
    {
        return new class($willReturn) implements WrappingParameterInterface, InputTypeParameterInterface {
            public function __construct(private InputTypeParameterInterface $willReturn)
            {
            }

            public function getType(): InputType
            {
            }

            public function hasDefaultValue(): bool
            {
            }

            public function getDefaultValue()
            {
            }

            public function resolve(?object $source, array $args, $context, ResolveInfo $info)
            {
            }

            public function getWrappedParameter(bool $recurse = false): ParameterInterface
            {
                return $this->willReturn;
            }
        };
    }
}
