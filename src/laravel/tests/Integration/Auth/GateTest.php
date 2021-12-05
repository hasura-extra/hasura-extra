<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Integration\Auth;

use Hasura\Laravel\Tests\TestCase;
use Illuminate\Auth\GenericUser;
use Illuminate\Contracts\Auth\Access\Gate;

final class GateTest extends TestCase
{
    public function testCheckAnonymous(): void
    {
        $gate = $this->app[Gate::class];

        $this->assertTrue($gate->check('anonymous'));
        $this->assertFalse($gate->check('admin'));
    }

    public function testCheckAuthenticatedUser(): void
    {
        $gate = $this->app[Gate::class];
        $user = new class([]) extends GenericUser {
            public function getRoles(): array
            {
                return ['user', 'manager'];
            }
        };

        $this->assertFalse($gate->forUser($user)->check('anonymous'));
        $this->assertFalse($gate->forUser($user)->check('admin'));
        $this->assertTrue($gate->forUser($user)->check('user'));
        $this->assertTrue($gate->forUser($user)->check('manager'));

        // inherited roles of manager
        $this->assertTrue($gate->forUser($user)->check('leader'));
    }

    public function testCheckAuthenticatedUserWithoutRoles(): void
    {
        $gate = $this->app[Gate::class];
        $user = new class([]) extends GenericUser {
        };

        $this->assertFalse($gate->forUser($user)->check('anonymous'));
        $this->assertFalse($gate->forUser($user)->check('user'));
    }
}
