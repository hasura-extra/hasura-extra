<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Integration\Metadata;

use Hasura\Laravel\Tests\TestCase;

final class ReloadCommandTest extends TestCase
{
    public function testReload(): void
    {
        $tester = $this->artisan('hasura:metadata:reload');
        $tester->assertSuccessful();
        $tester->run();
    }
}
