<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Integration\Metadata;

use Hasura\ApiClient\Client;

final class ApplyCommandTest extends MetadataTestCase
{
    public function testApply(): void
    {
        $old = $this->app[Client::class]->metadata()->query('export_metadata', [], 2);

        $this->artisan('hasura:metadata:export')->run();

        $tester = $this->artisan('hasura:metadata:apply');

        $tester->assertSuccessful();
        $tester->run();

        $new = $this->app[Client::class]->metadata()->query('export_metadata', [], 2);

        $this->assertSame($old['resource_version'] + 1, $new['resource_version']);
    }
}