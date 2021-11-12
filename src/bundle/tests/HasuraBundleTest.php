<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests;

use Hasura\SailorBridge\EndpointConfig;
use Spawnia\Sailor\Configuration;

final class HasuraBundleTest extends KernelTestCase
{
    public function testBoot()
    {
        self::bootKernel();

        $this->assertInstanceOf(EndpointConfig::class, Configuration::endpoint('hasura'));
    }
}