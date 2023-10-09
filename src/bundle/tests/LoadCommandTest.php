<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests;

use Hasura\Metadata\Command\ApplyMetadata;
use Hasura\Metadata\Command\ClearMetadata;
use Hasura\Metadata\Command\DropInconsistentMetadata;
use Hasura\Metadata\Command\ExportMetadata;
use Hasura\Metadata\Command\GetInconsistentMetadata;
use Hasura\Metadata\Command\PersistState;
use Hasura\Metadata\Command\ReloadMetadata;
use Hasura\SailorBridge\Command\Codegen;
use Hasura\SailorBridge\Command\Introspect;
use Symfony\Bundle\FrameworkBundle\Console\Application;

final class LoadCommandTest extends KernelTestCase
{
    public function testLoad(): void
    {
        $app = new Application(self::bootKernel());

        $this->assertTrue($app->has('hasura:metadata:apply'));
        $this->assertInstanceOf(
            ApplyMetadata::class,
            $app->get('hasura:metadata:apply')->getCommand()
        );

        $this->assertTrue($app->has('hasura:metadata:clear'));
        $this->assertInstanceOf(
            ClearMetadata::class,
            $app->get('hasura:metadata:clear')->getCommand()
        );

        $this->assertTrue($app->has('hasura:metadata:drop-inconsistent'));
        $this->assertInstanceOf(
            DropInconsistentMetadata::class,
            $app->get('hasura:metadata:drop-inconsistent')->getCommand()
        );

        $this->assertTrue($app->has('hasura:metadata:export'));
        $this->assertInstanceOf(
            ExportMetadata::class,
            $app->get('hasura:metadata:export')->getCommand()
        );

        $this->assertTrue($app->has('hasura:metadata:get-inconsistent'));
        $this->assertInstanceOf(
            GetInconsistentMetadata::class,
            $app->get('hasura:metadata:get-inconsistent')->getCommand()
        );

        $this->assertTrue($app->has('hasura:metadata:reload'));
        $this->assertInstanceOf(
            ReloadMetadata::class,
            $app->get('hasura:metadata:reload')->getCommand()
        );

        $this->assertTrue($app->has('hasura:metadata:persist-state'));
        $this->assertInstanceOf(
            PersistState::class,
            $app->get('hasura:metadata:persist-state')->getCommand()
        );
    }
}
