<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Integration\GraphQLite;

final class ArgNamingTest extends TestCase
{
    public function testNaming(): void
    {
        $query = /** @lang GraphQL */ <<<GQL
query {
    arg_naming_tests(snake_case: "test")
}
GQL;
        $this->execute($query);
        $this->assertResponseIsSuccessful();

        $data = $this->responseData();

        $this->assertSame('input: test', $data['data']['arg_naming_tests']);
    }
}
