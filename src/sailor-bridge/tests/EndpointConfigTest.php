<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\SailorBridge\Tests;

use GraphQL\Type\Schema;
use Hasura\ApiClient\Client;
use Hasura\SailorBridge\EndpointConfig;
use Hasura\SailorBridge\SailorClient;
use Hasura\SailorBridge\Type\DateTimeTypeConfig;
use Hasura\SailorBridge\Type\JsonTypeConfig;
use Hasura\SailorBridge\Type\UuidTypeConfig;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class EndpointConfigTest extends PHPUnitTestCase
{
    public function testConstructor(): void
    {
        $apiClient = new Client('');
        $sailorClient = new SailorClient($apiClient);
        $config = new EndpointConfig($sailorClient, '1', '2', '3', '4');

        $this->assertSame($sailorClient, $config->makeClient());
        $this->assertSame('1', $config->namespace());
        $this->assertSame('2', $config->targetPath());
        $this->assertSame('3', $config->searchPath());
        $this->assertSame('4', $config->schemaPath());
    }

    public function testConfigureTypes(): void
    {
        $apiClient = new Client('');
        $sailorClient = new SailorClient($apiClient);
        $config = new EndpointConfig($sailorClient, '1', '2', '3', '4');
        $types = $config->configureTypes($this->createMock(Schema::class), 'hasura');

        $this->assertArrayHasKey('json', $types);
        $this->assertArrayHasKey('jsonb', $types);
        $this->assertArrayHasKey('date', $types);
        $this->assertArrayHasKey('timetz', $types);
        $this->assertArrayHasKey('timestamptz', $types);
        $this->assertArrayHasKey('uuid', $types);

        $this->assertInstanceOf(JsonTypeConfig::class, $types['json']);
        $this->assertInstanceOf(JsonTypeConfig::class, $types['jsonb']);
        $this->assertInstanceOf(DateTimeTypeConfig::class, $types['date']);
        $this->assertInstanceOf(DateTimeTypeConfig::class, $types['timetz']);
        $this->assertInstanceOf(DateTimeTypeConfig::class, $types['timestamptz']);
        $this->assertInstanceOf(UuidTypeConfig::class, $types['uuid']);
    }
}
