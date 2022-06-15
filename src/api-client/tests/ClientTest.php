<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\ApiClient\Tests;

use Hasura\ApiClient\Client;
use Hasura\ApiClient\ConfigApi;
use Hasura\ApiClient\GraphqlApi;
use Hasura\ApiClient\MetadataApi;
use Hasura\ApiClient\RelayGraphqlApi;
use Hasura\ApiClient\VersionApi;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;

final class ClientTest extends TestCase
{
    use ClientSetupTrait;

    public function testUseCustomHttpClient(): void
    {
        $customHttpClient = HttpClient::create([
            'headers' => [
                'x-test' => true,
            ],
        ]);
        $client = new Client('http://localhost:8081', '123456', [], $customHttpClient);
        $refProp = new \ReflectionProperty($client, 'httpClient');
        $refProp->setAccessible(true);

        $httpClient = $refProp->getValue($client);
        $this->assertNotSame($customHttpClient, $httpClient);
    }

    public function testMetadata(): void
    {
        $this->assertInstanceOf(MetadataApi::class, $this->client->metadata());
    }

    public function testVersion(): void
    {
        $this->assertInstanceOf(VersionApi::class, $this->client->version());
    }

    public function testGraphql(): void
    {
        $this->assertInstanceOf(GraphqlApi::class, $this->client->graphql());
    }

    public function testRelayGraphql(): void
    {
        $this->assertInstanceOf(RelayGraphqlApi::class, $this->client->relayGraphql());
    }

    public function testConfig(): void
    {
        $this->assertInstanceOf(ConfigApi::class, $this->client->config());
    }
}
