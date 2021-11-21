<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Tests\Parameter;

use Hasura\GraphQLiteBridge\Attribute\ArgNaming as ArgNamingAttribute;
use Hasura\GraphQLiteBridge\Parameter\ArgNaming;
use Hasura\GraphQLiteBridge\Parameter\ArgNamingMiddleware;
use Hasura\GraphQLiteBridge\Parameter\ArgNamingParameterInterface;
use Hasura\GraphQLiteBridge\Tests\TestCase;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Type;
use TheCodingMachine\GraphQLite\Annotations\ParameterAnnotations;
use TheCodingMachine\GraphQLite\GraphQLRuntimeException;
use TheCodingMachine\GraphQLite\Mappers\Parameters\ParameterHandlerInterface;
use TheCodingMachine\GraphQLite\Parameters\InputTypeParameterInterface;
use TheCodingMachine\GraphQLite\Parameters\ParameterInterface;

final class ArgNamingMiddlewareTest extends TestCase
{
    public function testUseArgNamingAttribute(): void
    {
        $middleware = new ArgNamingMiddleware();
        $wrappedInput = $this->createMock(InputTypeParameterInterface::class);
        $handler = $this->createMockParameterHandler($wrappedInput);
        $refParameter = $this->createMock(\ReflectionParameter::class);

        $refParameter->expects($this->once())->method('getName')->willReturn('');

        $result = $middleware->mapParameter(
            $refParameter,
            new DocBlock(),
            $this->createMock(Type::class),
            new ParameterAnnotations([new ArgNamingAttribute('', '')]),
            $handler
        );

        $this->assertNotSame($result, $wrappedInput);
        $this->assertInstanceOf(ArgNaming::class, $result);
    }

    public function testNotUseArgNamingAttribute(): void
    {
        $middleware = new ArgNamingMiddleware();
        $wrappedInput = $this->createMock(InputTypeParameterInterface::class);
        $handler = $this->createMockParameterHandler($wrappedInput);

        $result = $middleware->mapParameter(
            $this->createMock(\ReflectionParameter::class),
            new DocBlock(),
            $this->createMock(Type::class),
            new ParameterAnnotations([]),
            $handler
        );

        $this->assertSame($result, $wrappedInput);
    }

    public function testThrowExceptionWhenNamingNormalParameter(): void
    {
        $this->expectException(GraphQLRuntimeException::class);
        $this->expectExceptionMessageMatches('~attribute only support input parameter$~');

        $middleware = new ArgNamingMiddleware();
        $wrappedInput = $this->createMock(ParameterInterface::class);
        $handler = $this->createMockParameterHandler($wrappedInput);

        $middleware->mapParameter(
            $this->createMock(\ReflectionParameter::class),
            new DocBlock(),
            $this->createMock(Type::class),
            new ParameterAnnotations([new ArgNamingAttribute('', '')]),
            $handler
        );
    }

    public function testThrowExceptionWhenAlreadyNamingBefore(): void
    {
        $this->expectException(GraphQLRuntimeException::class);
        $this->expectExceptionMessageMatches('~have been set arg name!$~');

        $middleware = new ArgNamingMiddleware();
        $wrappedInput = $this->createMock(ArgNamingParameterInterface::class);
        $handler = $this->createMockParameterHandler($wrappedInput);

        $middleware->mapParameter(
            $this->createMock(\ReflectionParameter::class),
            new DocBlock(),
            $this->createMock(Type::class),
            new ParameterAnnotations([new ArgNamingAttribute('', '')]),
            $handler
        );
    }

    private function createMockParameterHandler(ParameterInterface $willReturn): ParameterHandlerInterface
    {
        $handler = $this->createMock(ParameterHandlerInterface::class);

        $handler
            ->expects($this->once())
            ->method('mapParameter')
            ->willReturn($willReturn);

        return $handler;
    }
}
