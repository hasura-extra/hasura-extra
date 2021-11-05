<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Tests;

use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\NamedType;
use GraphQL\Type\Definition\OutputType;
use Hasura\GraphQLiteBridge\RootTypeMapper;
use Hasura\GraphQLiteBridge\ScalarType\Date;
use Hasura\GraphQLiteBridge\ScalarType\Json;
use Hasura\GraphQLiteBridge\ScalarType\Jsonb;
use Hasura\GraphQLiteBridge\ScalarType\Timestamptz;
use Hasura\GraphQLiteBridge\ScalarType\Timetz;
use Hasura\GraphQLiteBridge\ScalarType\Uuid;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Type;
use TheCodingMachine\GraphQLite\Mappers\Root\RootTypeMapperInterface;

final class RootTypeMapperTest extends TestCase
{
    public function testMapNameToType(): void
    {
        $nextMapper = $this->createMock(RootTypeMapperInterface::class);
        $fallbackNamedType = $this->createMock(NamedType::class);
        $nextMapper->expects($this->once())->method('mapNameToType')->willReturn($fallbackNamedType);

        $mapper = new RootTypeMapper($nextMapper);

        $this->assertInstanceOf(Date::class, $mapper->mapNameToType('date'));
        $this->assertInstanceOf(Jsonb::class, $mapper->mapNameToType('jsonb'));
        $this->assertInstanceOf(Json::class, $mapper->mapNameToType('json'));
        $this->assertInstanceOf(Timestamptz::class, $mapper->mapNameToType('timestamptz'));
        $this->assertInstanceOf(Timetz::class, $mapper->mapNameToType('timetz'));
        $this->assertInstanceOf(Uuid::class, $mapper->mapNameToType('uuid'));

        $this->assertSame($fallbackNamedType, $mapper->mapNameToType('fall back to next mapper'));
    }

    public function testCanPipeToNextMapper(): void
    {
        $returnInputType = $this->createMock(InputType::class);
        $returnOutputType = $this->createMock(OutputType::class);

        $nextMapper = $this->createMock(RootTypeMapperInterface::class);

        $nextMapper->expects($this->once())->method('toGraphQLInputType')->willReturn($returnInputType);
        $nextMapper->expects($this->once())->method('toGraphQLOutputType')->willReturn($returnOutputType);

        $mapper = new RootTypeMapper($nextMapper);

        $inputType = $mapper->toGraphQLInputType(
            $this->createMock(Type::class),
            null,
            'test',
            null,
            new DocBlock()
        );

        $this->assertSame($returnInputType, $inputType);

        $outputType = $mapper->toGraphQLOutputType(
            $this->createMock(Type::class),
            null,
            null,
            new DocBlock()
        );

        $this->assertSame($returnOutputType, $outputType);
    }
}