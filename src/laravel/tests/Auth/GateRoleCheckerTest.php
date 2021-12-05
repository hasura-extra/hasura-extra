<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Auth;

use Hasura\Laravel\Auth\GateRoleChecker;
use Hasura\Laravel\Auth\InheritanceRole;
use Hasura\Laravel\Tests\TestCase;
use Illuminate\Auth\GenericUser;

final class GateRoleCheckerTest extends TestCase
{
    public function testCheckAnonymous(): void
    {
        $checker = new GateRoleChecker('anonymous', new InheritanceRole([]));

        $this->assertTrue($checker->check(null, 'anonymous'));
        $this->assertNull($checker->check(null, 'admin'));
    }

    public function testCheckAuthenticatedUser(): void
    {
        $user = new class([]) extends GenericUser {
            public function getRoles(): array
            {
                return ['user', 'manager'];
            }
        };

        $checker = new GateRoleChecker('anonymous', new InheritanceRole([]));

        $this->assertNull($checker->check($user, 'anonymous'));
        $this->assertNull($checker->check($user, 'admin'));
        $this->assertTrue($checker->check($user, 'user'));
        $this->assertTrue($checker->check($user, 'manager'));
    }

    public function testCheckAuthenticatedUserWithoutRoles(): void
    {
        $user = new class([]) extends GenericUser {
        };

        $checker = new GateRoleChecker('anonymous', new InheritanceRole([]));

        $this->assertNull($checker->check($user, 'anonymous'));
        $this->assertNull($checker->check($user, 'user'));
    }
}
