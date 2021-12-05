<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Integration\Sailor;

use Hasura\Laravel\Tests\TestCase;
use Symfony\Component\Filesystem\Filesystem;

final class IntrospectCommandTest extends TestCase
{
    protected function tearDown(): void
    {
        (new Filesystem())->remove(config('hasura.sailor.schema_path'));

        parent::tearDown();
    }

    public function testIntrospect(): void
    {
        $this->assertFileDoesNotExist(config('hasura.sailor.schema_path'));

        $tester = $this->artisan('hasura:sailor:introspect');
        $tester->run();

        $tester->assertSuccessful();
        $this->assertFileExists(config('hasura.sailor.schema_path'));
    }
}
