<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLScalars\Tests;

use GraphQL\Language\AST\BooleanValueNode;
use GraphQL\Language\AST\ListValueNode;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;
use Hasura\GraphQLScalars\Uuid;
use Symfony\Component\Uid\Uuid as SymfonyUuid;

class UuidTest extends AbstractScalarTypeTest
{
    protected function makeInstance(): ScalarType
    {
        return new Uuid();
    }

    public function valuesToSerialize(): iterable
    {
        yield 'v1' => [
            $v1 = SymfonyUuid::v1(),
            $v1->toRfc4122(),
        ];
        yield 'v3' => [
            $v3 = SymfonyUuid::v3($v1, __METHOD__),
            $v3->toRfc4122(),
        ];
        yield 'v4' => [
            $v4 = SymfonyUuid::v4(),
            $v4->toRfc4122(),
        ];
        yield 'v5' => [
            $v5 = SymfonyUuid::v5($v4, __METHOD__),
            $v5->toRfc4122(),
        ];
        yield 'v6' => [
            $v6 = SymfonyUuid::v6(),
            $v6->toRfc4122(),
        ];
    }

    public function valuesToParse(): iterable
    {
        yield 'max' => [
            'ffffffff-ffff-ffff-ffff-ffffffffffff',
            SymfonyUuid::fromRfc4122('ffffffff-ffff-ffff-ffff-ffffffffffff'),
        ];
        yield 'min' => [
            '00000000-0000-0000-0000-000000000000',
            SymfonyUuid::fromRfc4122('00000000-0000-0000-0000-000000000000'),
        ];
    }

    public function nodesToParseLiteral(): iterable
    {
        yield 'max' => [
            new StringValueNode([
                'value' => 'ffffffff-ffff-ffff-ffff-ffffffffffff',
            ]),
            SymfonyUuid::fromRfc4122('ffffffff-ffff-ffff-ffff-ffffffffffff'),
        ];
        yield 'min' => [
            new StringValueNode([
                'value' => '00000000-0000-0000-0000-000000000000',
            ]),
            SymfonyUuid::fromRfc4122('00000000-0000-0000-0000-000000000000'),
        ];
    }

    public function invalidValuesToSerialize(): iterable
    {
        yield 'empty string' => [''];
        yield 'invalid uuid' => ['ffffffff'];
        yield 'invalid object' => [new \stdClass()];
        yield 'boolean' => [true];
        yield 'array' => [range(0, 99)];
    }

    public function invalidValuesToParse(): iterable
    {
        yield 'empty string' => [''];
        yield 'invalid uuid' => ['ffffffff'];
        yield 'boolean' => [true];
        yield 'array' => [range(0, 99)];
    }

    public function invalidNodesToParseLiteral(): iterable
    {
        yield 'empty string' => [
            new StringValueNode([
                'value' => '',
            ]), ];
        yield 'invalid uuid' => [
            new StringValueNode([
                'value' => 'ffffffff',
            ]), ];
        yield 'boolean' => [
            new BooleanValueNode([
                'value' => false,
            ]), ];
        yield 'list' => [
            new ListValueNode([
                'values' => [],
            ]), ];
    }
}
