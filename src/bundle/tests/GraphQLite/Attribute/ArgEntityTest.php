<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\GraphQLite\Attribute;

use Hasura\Bundle\GraphQLite\Attribute\ArgEntity;
use PHPUnit\Framework\TestCase;

final class ArgEntityTest extends TestCase
{
    public function testConstructor(): void
    {
        $attribute = new ArgEntity('1', '2', '3', '4', '5');

        $this->assertSame('1', $attribute->getTarget());
        $this->assertSame('2', $attribute->getArgName());
        $this->assertSame('3', $attribute->getFieldName());
        $this->assertSame('4', $attribute->getInputType());
        $this->assertSame('5', $attribute->getEntityManager());
    }
}