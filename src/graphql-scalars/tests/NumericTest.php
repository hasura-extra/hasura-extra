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
use GraphQL\Language\AST\FloatValueNode;
use GraphQL\Language\AST\IntValueNode;
use GraphQL\Language\AST\ListValueNode;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;
use Hasura\GraphQLScalars\Numeric;

class NumericTest extends AbstractScalarTypeTest
{
    public function testName(): void
    {
        $this->assertSame('numeric', $this->makeInstance()->name);
    }

    protected function makeInstance(): ScalarType
    {
        return new Numeric();
    }

    public function valuesToSerialize(): iterable
    {
        yield 'int value' => [1, 1];
        yield 'float value' => [2.0, 2.0];
    }

    public function valuesToParse(): iterable
    {
        yield 'int value' => [1, 1];
        yield 'float value' => [2.0, 2.0];
    }

    public function nodesToParseLiteral(): iterable
    {
        yield 'int node' => [new IntValueNode([
            'value' => 1,
        ]), 1];
        yield 'float value' => [new FloatValueNode([
            'value' => 2.0,
        ]), 2.0];
    }

    public function invalidValuesToSerialize(): iterable
    {
        yield 'boolean value' => [true];
        yield 'array value' => [[]];
        yield 'object value' => [new \stdClass()];
        yield 'string value' => ['abc'];
    }

    public function invalidValuesToParse(): iterable
    {
        yield 'boolean' => [true];
        yield 'array' => [[]];
        yield 'object' => [new \stdClass()];
        yield 'string value' => ['abc'];
    }

    public function invalidNodesToParseLiteral(): iterable
    {
        yield 'boolean node' => [
            new  BooleanValueNode([
                'value' => true,
            ]), ];
        yield 'string node' => [
            new  StringValueNode([
                'value' => 'abc',
            ]), ];
        yield 'list node' => [
            new  ListValueNode([
                'values' => [],
            ]), ];
    }
}
