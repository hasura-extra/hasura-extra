<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Tests\Controller;

use GraphQL\GraphQL;
use Hasura\GraphQLiteBridge\Tests\TestCase;

final class DummyQueryTest extends TestCase
{
    public function testCanExecuteDummyQuery(): void
    {
        $result = GraphQL::executeQuery($this->schema, 'query { _dummy }');

        $this->assertSame('dummy', $result->data['_dummy']);
    }
}
