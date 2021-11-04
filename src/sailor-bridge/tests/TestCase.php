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
    protected const CODEGEN_PATH = __DIR__ . '/.codegen';

    protected const SCHEMA_PATH = __DIR__ . '/Fixture/schema.graphql';

    protected const QUERY_SPEC_PATH = __DIR__ . '/Fixture/query_spec';

    protected Filesystem $filesystem;

    protected function setUp(): void
    {
        parent::setUp();

        $apiClient = new Client('http://localhost:8080', 'test');
        $config = new EndpointConfig(
            new SailorClient($apiClient),
            'App\GraphqlExecutor',
            self::CODEGEN_PATH,
            self::QUERY_SPEC_PATH,
            self::SCHEMA_PATH
        );

        Configuration::setEndpoint('hasura', $config);

        $this->filesystem = new Filesystem();
        $this->filesystem->mkdir(self::CODEGEN_PATH);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->filesystem->remove(self::CODEGEN_PATH);
    }
}
