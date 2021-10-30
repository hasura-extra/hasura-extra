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
use Hasura\SailorBridge\EndpointConfig;
use Hasura\SailorBridge\SailorClient;
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
}