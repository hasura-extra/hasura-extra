<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Tests\Parameter;

use Hasura\GraphQLiteBridge\Parameter\AvoidExplicitDefaultNull;
use Hasura\GraphQLiteBridge\Parameter\AvoidExplicitDefaultNullMiddleware;
use Hasura\GraphQLiteBridge\Tests\TestCase;
use phpDocumentor\Reflection\DocBlock;
use TheCodingMachine\GraphQLite\Annotations\ParameterAnnotations;
use TheCodingMachine\GraphQLite\Mappers\Parameters\ParameterHandlerInterface;
use TheCodingMachine\GraphQLite\Parameters\InputTypeParameterInterface;
use TheCodingMachine\GraphQLite\Parameters\ParameterInterface;

final class AvoidExplicitDefaultNullMiddlewareTest extends TestCase
{
    public function testCanWrapInputTypeParameter(): void
    {
        $middleware = new AvoidExplicitDefaultNullMiddleware();
        $parameterHandler = $this->createMockParameterHandler(InputTypeParameterInterface::class);

        $parameter = $middleware->mapParameter(
            $this->createMock(\ReflectionParameter::class),
            new DocBlock(),
            null,
            new ParameterAnnotations([]),
            $parameterHandler
        );

        $this->assertInstanceOf(AvoidExplicitDefaultNull::class, $parameter);
    }

    public function testCanAvoidParameter(): void
    {
        $middleware = new AvoidExplicitDefaultNullMiddleware();
        $parameterHandler = $this->createMockParameterHandler(ParameterInterface::class);

        $parameter = $middleware->mapParameter(
            $this->createMock(\ReflectionParameter::class),
            new DocBlock(),
            null,
            new ParameterAnnotations([]),
            $parameterHandler
        );

        $this->assertNotInstanceOf(AvoidExplicitDefaultNull::class, $parameter);
    }

    private function createMockParameterHandler(string $interface): ParameterHandlerInterface
    {
        $parameterHandler = $this->createMock(ParameterHandlerInterface::class);
        $parameterHandler
            ->expects(
                $this->once()
            )
            ->method('mapParameter')
            ->willReturn(
                $this->createMock($interface)
            );

        return $parameterHandler;
    }
}