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
use PHPUnit\Framework\TestCase as PHPTestCase;
use Spawnia\Sailor\Configuration;
use Symfony\Component\Filesystem\Filesystem;

class TestCase extends PHPTestCase
{
    protected const EXECUTOR_PATH = __DIR__ . '/Fixture/Generation';

    protected const SCHEMA_PATH = __DIR__ . '/Fixture/schema.graphql';

    protected const EXPECTED_SCHEMA_PATH = __DIR__ . '/../metadata/schema.graphql';

    protected const QUERY_SPEC_PATH = __DIR__ . '/Fixture/query_spec';

    protected Filesystem $filesystem;

    protected function setUp(): void
    {
        parent::setUp();

        $apiClient = new Client('http://localhost:8084', 'test');
        $config = new EndpointConfig(
            new SailorClient($apiClient),
            'Hasura\SailorBridge\Tests\Fixture\Generation',
            self::EXECUTOR_PATH,
            self::QUERY_SPEC_PATH,
            self::SCHEMA_PATH
        );

        Configuration::setEndpoint('hasura', $config);

        $this->filesystem = new Filesystem();
        $this->filesystem->mkdir(self::EXECUTOR_PATH);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->filesystem->remove(self::EXECUTOR_PATH);
    }
}
