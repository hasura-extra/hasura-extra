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
use Spawnia\Sailor\Type\TypeConfig;

class EndpointConfigTest extends PHPUnitTestCase
{
    public function testConstructor(): void
    {
        $config = $this->initConfig();

        $this->assertInstanceOf(\Spawnia\Sailor\Client::class, $config->makeClient());
        $this->assertSame('1', $config->namespace());
        $this->assertSame('2', $config->targetPath());
        $this->assertSame('3', $config->searchPath());
        $this->assertSame('4', $config->schemaPath());
    }

    public function testConfigureTypes(): void
    {
        $config = $this->initConfig();
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
        $this->assertInstanceOf(DateTimeTypeConfig::class, $types['time']);
        $this->assertInstanceOf(DateTimeTypeConfig::class, $types['timetz']);
        $this->assertInstanceOf(DateTimeTypeConfig::class, $types['timestamp']);
        $this->assertInstanceOf(DateTimeTypeConfig::class, $types['timestamptz']);
        $this->assertInstanceOf(UuidTypeConfig::class, $types['uuid']);
    }

    public function testAddTypeConfig(): void
    {
        $type = $this->createMock(TypeConfig::class);
        $config = $this->initConfig();

        $config->addTypeConfig('test', $type);
        $types = $config->configureTypes($this->createMock(Schema::class), 'hasura');

        $this->assertArrayHasKey('test', $types);
        $this->assertSame($type, $types['test']);
    }

    private function initConfig(): EndpointConfig
    {
        $apiClient = new Client('');
        $sailorClient = new SailorClient($apiClient);

        return new EndpointConfig($sailorClient, '1', '2', '3', '4');
    }
}
