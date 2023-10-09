<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\DependencyInjection;

use Hasura\Bundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

final class ConfigurationTest extends TestCase
{
    public function testDefault(): void
    {
        $configuration = new Configuration();
        $configuration->getConfigTreeBuilder();
        $processor = new Processor();
        $config = $processor->processConfiguration($configuration, []);

        $this->assertSame(
            [
                'base_uri' => 'http://hasura:8080',
                'admin_secret' => null,
                'remote_schema_name' => null,
                'metadata' => [
                    'state_processors' => [
                        'enabled_inherited_roles' => true,
                    ],
                    'path' => '%kernel.project_dir%/hasura/metadata',
                ],
                'auth' => [
                    'anonymous_role' => 'ROLE_ANONYMOUS',
                    'default_role' => 'ROLE_USER',
                ],
            ],
            $config
        );
    }

    public function testCustomize(): void
    {
        $configuration = new Configuration();
        $configuration->getConfigTreeBuilder();
        $processor = new Processor();
        $config = $processor->processConfiguration(
            $configuration,
            [
                [
                    'base_uri' => 'http://localhost',
                    'admin_secret' => 'secret',
                    'remote_schema_name' => 'bundle',
                    'metadata' => [
                        'state_processors' => [
                            'enabled_inherited_roles' => false,
                        ],
                    ],
                ],
            ]
        );

        $this->assertSame(
            [
                'base_uri' => 'http://localhost',
                'admin_secret' => 'secret',
                'remote_schema_name' => 'bundle',
                'metadata' => [
                    'state_processors' => [
                        'enabled_inherited_roles' => false,
                    ],
                    'path' => '%kernel.project_dir%/hasura/metadata',
                ],
                'auth' => [
                    'anonymous_role' => 'ROLE_ANONYMOUS',
                    'default_role' => 'ROLE_USER',
                ],
            ],
            $config
        );
    }
}
