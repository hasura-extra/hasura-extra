<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Integration\Metadata;

final class GetInconsistentCommandTest extends MetadataTestCase
{
    public function testConsistent(): void
    {
        $tester = $this->artisan('hasura:metadata:get-inconsistent');
        $tester->assertSuccessful();
        $tester->run();
    }

    public function testInconsistent(): void
    {
        $this->putInconsistentTable();

        $tester = $this->artisan('hasura:metadata:get-inconsistent');
        $tester->assertFailed();
        $tester->run();
    }
}