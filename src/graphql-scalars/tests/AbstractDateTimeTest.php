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
use GraphQL\Language\AST\NodeList;
use GraphQL\Language\AST\ObjectValueNode;
use GraphQL\Language\AST\StringValueNode;

abstract class AbstractDateTimeTest extends AbstractScalarTypeTest
{
    public function invalidNodesToParseLiteral(): iterable
    {
        yield 'invalid value node' => [
            new StringValueNode([
                'value' => '',
            ]), ];
        yield 'boolean node' => [
            new BooleanValueNode([
                'value' => true,
            ]), ];
        yield 'list node' => [
            new ListValueNode([
                'values' => new NodeList([]),
            ]), ];
        yield 'object node' => [
            new ObjectValueNode([
                'fields' => new NodeList([]),
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

    public function invalidValuesToSerialize(): iterable
    {
        yield 'invalid value' => [''];
        yield 'mutate datetime' => [new \DateTime()];
        yield 'boolean' => [true];
        yield 'array' => [[]];
        yield 'object' => [new \stdClass()];
    }

    public function invalidValuesToParse(): iterable
    {
        yield 'invalid value' => [''];
        yield 'mutate datetime' => [new \DateTime()];
        yield 'boolean' => [true];
        yield 'array' => [[]];
        yield 'object' => [new \stdClass()];
    }
}
