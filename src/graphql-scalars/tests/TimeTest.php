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
use Hasura\GraphQLScalars\Time;

class TimeTest extends AbstractDateTimeTest
{
    public function testName(): void
    {
        $this->assertSame('time', $this->makeInstance()->name);
    }

    protected function makeInstance(): ScalarType
    {
        return new Time();
    }

    public function valuesToSerialize(): iterable
    {
        yield 'date' => [
            new \DateTimeImmutable('2022-02-02'),
            '00:00:00',
        ];
        yield 'datetime' => [
            new \DateTimeImmutable('2022-02-02T02:02:02'),
            '02:02:02',
        ];
        yield 'time' => [
            new \DateTimeImmutable('01:01:01'),
            '01:01:01',
        ];
        yield 'time with timezone' => [
            new \DateTimeImmutable('02:02:02+02:02'),
            '02:02:02',
        ];
    }

    public function valuesToParse(): iterable
    {
        yield 'immutable instance' => [
            \DateTimeImmutable::createFromFormat('H:i:s', '01:01:01'),
            \DateTimeImmutable::createFromFormat('H:i:s|', '01:01:01'),
        ];
        yield '01:01:01' => [
            '01:01:01',
            \DateTimeImmutable::createFromFormat('H:i:s|', '01:01:01'),
        ];
        yield '02:02:02' => [
            '02:02:02',
            \DateTimeImmutable::createFromFormat('H:i:s|', '02:02:02'),
        ];
    }

    public function nodesToParseLiteral(): iterable
    {
        yield '01:01:01' => [
            new StringValueNode([
                'value' => '01:01:01',
            ]),
            \DateTimeImmutable::createFromFormat('H:i:s|', '01:01:01'),
        ];
        yield '02:02:02' => [
            new StringValueNode([
                'value' => '02:02:02',
            ]),
            \DateTimeImmutable::createFromFormat('H:i:s|', '02:02:02'),
        ];
    }

    public function invalidValuesToParse(): iterable
    {
        yield from parent::invalidValuesToParse();
        yield 'datetime' => ['2022-02-02T02:02:02'];
        yield 'datetime with timezone' => ['2022-02-02T02:02:02+02:02'];
        yield 'date' => ['2022-02-02'];
        yield 'time with timezone' => ['02:02:02+02:02'];
    }
}
