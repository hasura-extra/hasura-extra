<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLScalars\Tests;

use GraphQL\Language\AST\FloatValueNode;
use GraphQL\Language\AST\IntValueNode;
use GraphQL\Language\AST\ListValueNode;
use GraphQL\Language\AST\NameNode;
use GraphQL\Language\AST\NodeList;
use GraphQL\Language\AST\ObjectFieldNode;
use GraphQL\Language\AST\ObjectValueNode;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;
use Hasura\GraphQLScalars\Json;
use Hasura\GraphQLScalars\Jsonb;
use JetBrains\PhpStorm\Internal\TentativeType;

class JsonTest extends AbstractScalarTypeTest
{
    public function testName()
    {
        $this->assertSame('json', $this->makeInstance()->name);
    }

    protected function makeInstance(): ScalarType
    {
        return new Json();
    }

    public function valuesToSerialize(): iterable
    {
        yield 'array list' => [[1, 2, 3], [1, 2, 3]];
        yield 'array assoc' => [[
            'a' => 'b',
        ], [
            'a' => 'b',
        ]];
        yield 'json serialize instance' => [
            new class() implements \JsonSerializable {
                public function jsonSerialize(): array
                {
                    return [1, 2, 3];
                }
            },
            [1, 2, 3],
        ];
    }

    public function valuesToParse(): iterable
    {
        yield 'array list' => [[1, 2, 3], [1, 2, 3]];
        yield 'array assoc' => [[
            'a' => 'b',
        ], [
            'a' => 'b',
        ]];
    }

    public function nodesToParseLiteral(): iterable
    {
        yield 'list node' => [
            new ListValueNode([
                'values' => new NodeList([
                    new IntValueNode([
                        'value' => '1',
                    ]),
                    new IntValueNode([
                        'value' => '2',
                    ]),
                    new IntValueNode(
                        [
                            'value' =>
                            '3',
                        ]
                    ),
                ]),
            ]),
            [1, 2, 3],
        ];
        yield 'object value node' => [
            new ObjectValueNode([
                'fields' => new NodeList([
                    new ObjectFieldNode([
                        'name' => new NameNode([
                            'value' => 'a',
                        ]),
                        'value' => new StringValueNode([
                            'value' => 'b',
                            
                        ]),
                    ]),
                    new ObjectFieldNode([
                        'name' => new NameNode([
                            'value' => 'c',
                        ]),
                        'value' => new StringValueNode([
                            'value' =>
                            'd',
                            
                        ]),
                    ]),
                ]),
            ]),
            [
                'a' =>
                 'b',
                'c' => 'd',
            ],
        ];
    }

    public function invalidValuesToSerialize(): iterable
    {
        yield 'invalid scalar' => [1];
        yield 'invalid object' => [new \stdClass()];
    }

    public function invalidValuesToParse(): iterable
    {
        yield 'invalid scalar' => [1];
        yield 'invalid object' => [new \stdClass()];
    }

    public function invalidNodesToParseLiteral(): iterable
    {
        yield 'invalid scalar' => [
            new FloatValueNode([
                'value' => '1',
            ]), ];
    }
}
