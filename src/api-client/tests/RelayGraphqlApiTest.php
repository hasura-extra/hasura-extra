<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\ApiClient\Tests;

use PHPUnit\Framework\TestCase;

final class RelayGraphqlApiTest extends TestCase
{
    use ClientSetupTrait;

    public function testQueryHaveNodeField(): void
    {
        $data = $this->client->relayGraphql()->query('query Test { __schema { queryType { fields { name } } }  }', throwOnError: true);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('data', $data);
        $fields = $data['data']['__schema']['queryType']['fields'];
        $this->assertTrue(in_array('node', array_column($fields, 'name'), true));
    }
}
