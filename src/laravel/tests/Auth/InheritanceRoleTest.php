<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Auth;

use Hasura\Laravel\Auth\InheritanceRole;
use Hasura\Laravel\Tests\TestCase;

final class InheritanceRoleTest extends TestCase
{
    public function testGetReachableRoles(): void
    {
        $instance = new InheritanceRole(['admin' => ['user', 'manager']]);

        $this->assertSame(['admin', 'user', 'manager'], $instance->getReachableRoleNames(['admin']));
    }
}