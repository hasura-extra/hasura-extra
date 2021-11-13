<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;

final class LoadCommandTest extends KernelTestCase
{
    public function testLoad(): void
    {
        $app = new Application(self::bootKernel());

        $this->assertTrue($app->has('hasura:metadata:apply'));
        $this->assertTrue($app->has('hasura:metadata:clear'));
        $this->assertTrue($app->has('hasura:metadata:drop-inconsistent'));
        $this->assertTrue($app->has('hasura:metadata:export'));
        $this->assertTrue($app->has('hasura:metadata:get-inconsistent'));
        $this->assertTrue($app->has('hasura:metadata:reload'));
        $this->assertTrue($app->has('hasura:metadata:persist-state'));
        $this->assertTrue($app->has('hasura:sailor:introspect'));
        $this->assertTrue($app->has('hasura:sailor:codegen'));
    }
}