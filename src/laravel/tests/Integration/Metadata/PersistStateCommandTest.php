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

final class PersistStateCommandTest extends MetadataTestCase
{
    public function testPersistState(): void
    {
        $tester = $this->artisan('hasura:metadata:persist-state');
        $tester->assertSuccessful();
        $tester->run();

        $metadata = $this->app[ManagerInterface::class]->exportToArray();
        $inheritedRoles = array_column($metadata['inherited_roles'], null, 'role_name');

        // check inherited roles state processor
        $this->assertArrayHasKey('manager', $inheritedRoles);
        $this->assertSame(['leader'], $inheritedRoles['manager']['role_set']);

        // check remote schema permissions state processor
        $remoteSchemas = array_column($metadata['remote_schemas'], null, 'name');
        $permissionRoles = array_column($remoteSchemas['laravel']['permissions'], null, 'role');

        $this->assertArrayHasKey('user', $permissionRoles);
        $this->assertArrayHasKey('tester', $permissionRoles);
        $this->assertArrayHasKey('leader', $permissionRoles);
    }
}
