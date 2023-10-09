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
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;
use Hasura\GraphQLScalars\Character;

class CharacterTest extends AbstractScalarTypeTest
{
    public function testName()
    {
        $this->assertSame('character', $this->makeInstance()->name);
    }

    protected function makeInstance(): ScalarType
    {
        return new Character();
    }

    public function valuesToSerialize(): iterable
    {
        yield 'a' => ['a', 'a'];
        yield 'b' => ['b', 'b'];
        yield 'c' => ['c', 'c'];
    }

    public function valuesToParse(): iterable
    {
        yield 'a' => ['a', 'a'];
        yield 'b' => ['b', 'b'];
        yield 'c' => ['c', 'c'];
    }

    public function nodesToParseLiteral(): iterable
    {
        yield 'a' => [new StringValueNode([
            'value' => 'a',
        ]), 'a'];
        yield 'b' => [new StringValueNode([
            'value' => 'b',
        ]), 'b'];
        yield 'c' => [new StringValueNode([
            'value' => 'c',
        ]), 'c'];
    }

    public function invalidValuesToSerialize(): iterable
    {
        yield 'long string' => ['abc'];
        yield 'boolean type' => [false];
        yield 'array type' => [[]];
    }

    public function invalidValuesToParse(): iterable
    {
        yield 'long string' => ['abc'];
        yield 'boolean type' => [false];
        yield 'array type' => [[]];
    }

    public function invalidNodesToParseLiteral(): iterable
    {
        yield 'long string node' => [
            new StringValueNode([
                'value' => 'abc',
            ]), ];
        yield 'int node' => [
            new IntValueNode([
                'value' => '1',
            ]), ];
        yield 'float node' => [
            new FloatValueNode([
                'value' => '1.0',
            ]), ];
    }
}
