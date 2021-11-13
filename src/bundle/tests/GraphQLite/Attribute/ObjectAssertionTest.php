<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\GraphQLite\Attribute;

use Hasura\Bundle\GraphQLite\Attribute\ObjectAssertion;
use PHPUnit\Framework\TestCase;

final class ObjectAssertionTest extends TestCase
{
    public function testConstructor(): void
    {
        $attribute = new ObjectAssertion('1', '2', 3, [4]);

        $this->assertSame('1', $attribute->getTarget());
        $this->assertSame('2', $attribute->getGroups());
        $this->assertSame(3, $attribute->getMode());
        $this->assertSame([4], $attribute->getCustomViolationPropertyPaths());
    }
}