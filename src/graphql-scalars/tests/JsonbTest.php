<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLScalars\Tests;

use GraphQL\Type\Definition\ScalarType;
use Hasura\GraphQLScalars\Jsonb;

class JsonbTest extends JsonTest
{
    public function testName()
    {
        $this->assertSame('jsonb', (new Jsonb())->name);
    }

    protected function makeInstance(): ScalarType
    {
        return new Jsonb();
    }
}