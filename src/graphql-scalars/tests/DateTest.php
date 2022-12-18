<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLScalars\Tests;

use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;
use Hasura\GraphQLScalars\Date;

final class DateTest extends AbstractDateTimeTest
{
    public function testName()
    {
        $this->assertSame('date', $this->makeInstance()->name);
    }

    protected function makeInstance(): ScalarType
    {
        return new Date();
    }

    public function valuesToSerialize(): iterable
    {
        yield 'date' => [
            new \DateTimeImmutable('2022-02-02'),
            '2022-02-02',
        ];
        yield 'date time' => [
            new \DateTimeImmutable('2022-02-03T02:02:02'),
            '2022-02-03',
        ];
        yield 'date time with timezone' => [
            new \DateTimeImmutable('2022-02-04T02:02:02+02:02'),
            '2022-02-04',
        ];
    }

    public function valuesToParse(): iterable
    {
        yield 'date string' => [
            '2022-02-02',
            \DateTimeImmutable::createFromFormat('Y-m-d|', '2022-02-02'),
        ];
        yield 'date immutable' => [
            \DateTimeImmutable::createFromFormat('Y-m-d', '2022-02-02'),
            \DateTimeImmutable::createFromFormat('Y-m-d|', '2022-02-02'),
        ];
    }

    public function nodesToParseLiteral(): iterable
    {
        yield 'date' => [
            new StringValueNode([
                'value' => '2022-02-02',
            ]),
            \DateTimeImmutable::createFromFormat('Y-m-d|', '2022-02-02'),
        ];
    }

    public function invalidValuesToParse(): iterable
    {
        yield from parent::invalidValuesToParse();
        yield 'date time' => ['2022-02-02T02:02:02'];
        yield 'date time with timezone' => ['2022-02-02T02:02:02+02:02'];
    }

    public function invalidNodesToParseLiteral(): iterable
    {
        yield from parent::invalidNodesToParseLiteral();
        yield 'date time' => [
            new StringValueNode([
                'value' => '2022-02-02T02:02:02',
            ]), ];
        yield 'date time with timezone' => [
            new StringValueNode([
                'value' => '2022-02-02T02:02:02+02:02',
            ]), ];
    }
}
