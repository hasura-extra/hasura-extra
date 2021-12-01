<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\GraphQLite\Attribute;

use Hasura\Laravel\GraphQLite\Attribute\ValidateObject;
use Hasura\Laravel\Tests\TestCase;

final class ValidateObjectTest extends TestCase
{
    public function testGetters(): void
    {
        $attribute = new ValidateObject('1', [2]);

        $this->assertSame('1', $attribute->getTarget());
        $this->assertSame([2], $attribute->getCustomErrorArgumentNames());
    }
}