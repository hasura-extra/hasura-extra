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
use GraphQL\Type\Definition\ScalarType;
use Hasura\GraphQLScalars\Float8;

class Float8Test extends NumericTest
{
    public function testName(): void
    {
        $this->assertSame('float8', (new Float8())->name);
    }

    public function testParseResultIsFloat(): void
    {
        $this->assertIsFloat($this->makeInstance()->parseValue(2));
        $this->assertIsFloat($this->makeInstance()->parseValue(2.0));
    }

    public function testParseLiteralResultIsFloat(): void
    {
        $this->assertIsFloat($this->makeInstance()->parseLiteral(new IntValueNode([
            'value' => '2',
        ])));
        $this->assertIsFloat($this->makeInstance()->parseLiteral(new FloatValueNode([
            'value' => '2.0',
        ])));
    }

    public function testSerializeResultIsFloat(): void
    {
        $this->assertIsFloat($this->makeInstance()->serialize(2));
        $this->assertIsFloat($this->makeInstance()->serialize(2.0));
    }

    protected function makeInstance(): ScalarType
    {
        return new Float8();
    }
}
