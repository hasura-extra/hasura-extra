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

final class ExportCommandTest extends TestCase
{
    public function testExport()
    {
        $this->assertDirectoryDoesNotExist(config('hasura.metadata.path'));

        $tester = $this->artisan('hasura:metadata:export');
        $tester->assertSuccessful();
        $tester->run();

        $this->assertDirectoryExists(config('hasura.metadata.path'));
    }
}
