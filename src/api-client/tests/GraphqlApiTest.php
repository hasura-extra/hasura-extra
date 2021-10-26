<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\ApiClient\Tests;

use Hasura\ApiClient\GraphqlApiException;
use PHPUnit\Framework\TestCase;

final class GraphqlApiTest extends TestCase
{
    use ClientSetupTrait;

    public function testQuery(): void
    {
        $data = $this->client->graphql()->query('query Test { __typename }', throwOnError: true);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('data', $data);
        $this->assertSame('query_root', $data['data']['__typename']);
    }

    public function testQueryWithVariables(): void
    {
        $data = $this->client->graphql()->query(
            'query Test($include: Boolean!) { __typename @include(if: $include) }',
            [
                'include' => true,
            ],
            true
        );

        $this->assertIsArray($data);
        $this->assertArrayHasKey('data', $data);
        $this->assertSame('query_root', $data['data']['__typename']);
    }

    public function testQueryErrorThrowException(): void
    {
        $this->expectException(GraphqlApiException::class);

        $this->client->graphql()->query('', throwOnError: true);
    }

    public function testQueryErrorNotThrowException(): void
    {
        $data = $this->client->graphql()->query('');

        $this->assertIsArray($data);
        $this->assertArrayHasKey('errors', $data);
    }
}
