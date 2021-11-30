<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\GraphQLite\Attribute;

use Hasura\Laravel\GraphQLite\Attribute\ArgModel;
use Hasura\Laravel\Tests\TestCase;

final class ArgModelTest extends TestCase
{
    public function testGetters(): void
    {
        $attribute = new ArgModel('1', '2', '3', '4');

        $this->assertSame('1', $attribute->getTarget());
        $this->assertSame('2', $attribute->getArgName());
        $this->assertSame('3', $attribute->getFieldName());
        $this->assertSame('4', $attribute->getInputType());
    }
}