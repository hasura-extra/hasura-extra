<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Integration\Metadata;

use Hasura\Metadata\ManagerInterface;

final class ClearCommandTest extends MetadataTestCase
{
    public function testClear(): void
    {
        $tester = $this->artisan('hasura:metadata:clear');

        $tester->assertSuccessful();
        $tester->run();

        $metadata = $this->app[ManagerInterface::class]->exportToArray();

        $this->assertEmpty($metadata['sources'][0]['tables']);
    }
}