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
use Hasura\GraphQLScalars\Timestamp;

class TimestampTest extends AbstractDateTimeTest
{
    public function testName(): void
    {
        $this->assertSame('timestamp', $this->makeInstance()->name);
    }

    protected function makeInstance(): ScalarType
    {
        return new Timestamp();
    }

    public function valuesToSerialize(): iterable
    {
        yield 'date' => [
            new \DateTimeImmutable('2022-02-02'),
            '2022-02-02T00:00:00',
        ];
        yield 'datetime' => [
            new \DateTimeImmutable('2022-02-02T02:02:02'),
            '2022-02-02T02:02:02',
        ];
        yield 'datetime with timezone' => [
            new \DateTimeImmutable('2022-02-02T02:02:02+02:02'),
            '2022-02-02T02:02:02',
        ];
    }

    public function valuesToParse(): iterable
    {
        yield '2022-01-01T01:01:01' => [
            '2022-01-01T01:01:01',
            \DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s', '2022-01-01T01:01:01'),
        ];
        yield '2022-02-02T02:02:02' => [
            '2022-02-02T02:02:02',
            \DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s', '2022-02-02T02:02:02'),
        ];
    }

    public function nodesToParseLiteral(): iterable
    {
        yield '2022-01-01T01:01:01' => [
            new StringValueNode([
                'value' => '2022-01-01T01:01:01',
            ]),
            \DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s', '2022-01-01T01:01:01'),
        ];
        yield '2022-02-02T02:02:02' => [
            new StringValueNode([
                'value' => '2022-02-02T02:02:02',
            ]),
            \DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s', '2022-02-02T02:02:02'),
        ];
    }

    public function invalidValuesToParse(): iterable
    {
        yield from parent::invalidValuesToParse();
        yield 'date' => ['2022-02-02'];
        yield 'datetime with timezone' => ['2022-02-02T02:02:02+02:02'];
        yield 'time' => ['02:02:02'];
        yield 'time with timezone' => ['02:02:02+02:02'];
    }

    public function invalidNodesToParseLiteral(): iterable
    {
        yield from parent::invalidNodesToParseLiteral();
        yield 'date' => [
            new StringValueNode([
                'value' => '2022-02-02',
            ]), ];
        yield 'datetime with timezone' => [
            new StringValueNode([
                'value' => '2022-02-02T02:02:02+02:02',
            ]), ];
        yield 'time' => [
            new StringValueNode([
                'value' => '02:02:02',
            ]), ];
        yield 'time with timezone' => [
            new StringValueNode([
                'value' => '02:02:02+02:02',
            ]), ];
    }
}
