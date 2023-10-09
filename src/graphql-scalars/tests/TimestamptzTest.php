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
use Hasura\GraphQLScalars\Timestamptz;

class TimestamptzTest extends AbstractDateTimeTestCase
{
    public function testName(): void
    {
        $this->assertSame('timestamptz', $this->makeInstance()->name);
    }

    protected function makeInstance(): ScalarType
    {
        return new Timestamptz();
    }

    public static function valuesToSerialize(): iterable
    {
        yield 'date' => [
            new \DateTimeImmutable('2022-02-02'),
            '2022-02-02T00:00:00+00:00',
        ];
        yield 'datetime' => [
            new \DateTimeImmutable('2022-02-02T02:02:02'),
            '2022-02-02T02:02:02+00:00',
        ];
        yield 'datetime with timezone' => [
            new \DateTimeImmutable('2022-02-02T02:02:02+02:02'),
            '2022-02-02T02:02:02+02:02',
        ];
    }

    public static function valuesToParse(): iterable
    {
        yield '2022-01-01T01:01:01+00:00' => [
            '2022-01-01T01:01:01+00:00',
            \DateTimeImmutable::createFromFormat(\DateTimeInterface::ATOM, '2022-01-01T01:01:01+00:00'),
        ];
        yield '2022-02-02T02:02:02+00:00' => [
            '2022-02-02T02:02:02+02:02',
            \DateTimeImmutable::createFromFormat(\DateTimeInterface::ATOM, '2022-02-02T02:02:02+02:02'),
        ];
    }

    public static function nodesToParseLiteral(): iterable
    {
        yield '2022-01-01T01:01:01+00:00' => [
            new StringValueNode([
                'value' => '2022-01-01T01:01:01+00:00',
            ]),
            \DateTimeImmutable::createFromFormat(\DateTimeInterface::ATOM, '2022-01-01T01:01:01+00:00'),
        ];
        yield '2022-02-02T02:02:02+00:00' => [
            new StringValueNode([
                'value' => '2022-02-02T02:02:02+02:02',
            ]),
            \DateTimeImmutable::createFromFormat(\DateTimeInterface::ATOM, '2022-02-02T02:02:02+02:02'),
        ];
    }

    public static function invalidValuesToParse(): iterable
    {
        yield from parent::invalidValuesToParse();
        yield 'date' => ['2022-02-02'];
        yield 'datetime' => ['2022-02-02T02:02:02'];
        yield 'time' => ['02:02:02'];
        yield 'time with timezone' => ['02:02:02+02:02'];
    }

    public static function invalidNodesToParseLiteral(): iterable
    {
        yield from parent::invalidNodesToParseLiteral();
        yield 'date' => [
            new StringValueNode([
                'value' => '2022-02-02',
            ]), ];
        yield 'datetime' => [
            new StringValueNode([
                'value' => '2022-02-02T02:02:02',
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
