<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLScalars\Tests;

use GraphQL\Error\Error;
use GraphQL\Language\AST\Node;
use GraphQL\Type\Definition\ScalarType;
use PHPUnit\Framework\TestCase;

abstract class AbstractScalarTypeTestCase extends TestCase
{
    /**
     * @dataProvider valuesToSerialize
     */
    public function testSerialize(mixed $value, mixed $expected): void
    {
        $actual = $this->makeInstance()->serialize($value);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider valuesToParse
     */
    public function testParse(mixed $value, mixed $expected): void
    {
        $actual = $this->makeInstance()->parseValue($value);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider nodesToParseLiteral
     */
    public function testParseLiteral(Node $node, mixed $expected): void
    {
        $actual = $this->makeInstance()->parseLiteral($node);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider invalidValuesToSerialize
     */
    public function testSerializeWithInvalidValues(mixed $value): void
    {
        $this->expectException(\Exception::class);
        $this->makeInstance()->serialize($value);
    }

    /**
     * @dataProvider invalidValuesToParse
     */
    public function testParseWithInvalidValues(mixed $value): void
    {
        $this->expectException(Error::class);
        $this->makeInstance()->parseValue($value);
    }

    /**
     * @dataProvider invalidNodesToParseLiteral
     */
    public function testParseLiteralWithInvalidNodes(Node $node): void
    {
        $this->expectException(Error::class);
        $this->makeInstance()->parseLiteral($node);
    }

    abstract protected function makeInstance(): ScalarType;

    abstract public static function valuesToSerialize(): iterable;

    abstract public static function valuesToParse(): iterable;

    abstract public static function nodesToParseLiteral(): iterable;

    abstract public static function invalidValuesToSerialize(): iterable;

    abstract public static function invalidValuesToParse(): iterable;

    abstract public static function invalidNodesToParseLiteral(): iterable;
}
