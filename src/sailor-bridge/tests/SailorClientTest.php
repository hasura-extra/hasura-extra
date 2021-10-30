<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\SailorBridge\Tests;

use Hasura\ApiClient\Client;
use Hasura\SailorBridge\SailorClient;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Spawnia\Sailor\Response;

final class SailorClientTest extends PHPUnitTestCase
{
    private SailorClient $sailorClient;

    protected function setUp(): void
    {
        parent::setUp();

        $client = new Client('http://localhost:8080', 'test');
        $this->sailorClient = new SailorClient($client);
    }

    public function testRequest(): void
    {
        $response = $this->sailorClient->request('query { __typename }');
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame('query_root', $response->data->__typename);
    }

    public function testRequestWithVariables(): void
    {
        $response = $this->sailorClient->request(
            'query ($name: String!) { __type(name: $name) { name kind } }',
            (object)['name' => 'mutation_root']
        );

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame('mutation_root', $response->data->__type->name);
        $this->assertSame('OBJECT', $response->data->__type->kind);
    }
}