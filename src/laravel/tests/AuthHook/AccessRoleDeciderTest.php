<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\AuthHook;

use Hasura\AuthHook\UnauthorizedException;
use Hasura\Laravel\AuthHook\AccessRoleDecider;
use Hasura\Laravel\Tests\TestCase;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Auth\Guard;
use Psr\Http\Message\ServerRequestInterface;

final class AccessRoleDeciderTest extends TestCase
{
    public function testDecideRoleForAnonymous(): void
    {
        $guard = $this->createMock(Guard::class);
        $guard->expects($this->once())->method('user')->willReturn(null);

        $decider = new AccessRoleDecider(
            'anonymous',
            'user',
            $guard,
            $this->createMock(Gate::class)
        );

        $role = $decider->decideAccessRole($this->createMock(ServerRequestInterface::class));

        $this->assertSame('anonymous', $role);
    }

    public function testDecideDefaultRoleForUser(): void
    {
        $guard = $this->createMock(Guard::class);
        $guard->expects($this->once())->method('user')->willReturn(true);

        $gate = $this->createMock(Gate::class);
        $gate->expects($this->once())->method('check')->willReturn(true);

        $decider = new AccessRoleDecider(
            'anonymous',
            'user',
            $guard,
            $gate
        );

        $role = $decider->decideAccessRole($this->createMock(ServerRequestInterface::class));

        $this->assertSame('user', $role);
    }

    public function testDecideRequestedRoleForUser(): void
    {
        $guard = $this->createMock(Guard::class);
        $guard->expects($this->once())->method('user')->willReturn(true);

        $gate = $this->createMock(Gate::class);
        $gate->expects($this->once())->method('check')->willReturn(true);

        $serverRequest = $this->createMock(ServerRequestInterface::class);
        $serverRequest->expects($this->once())->method('getHeader')->willReturn(['admin']);

        $decider = new AccessRoleDecider(
            'anonymous',
            'user',
            $guard,
            $gate
        );

        $role = $decider->decideAccessRole($serverRequest);

        $this->assertSame('admin', $role);
    }

    public function testDecideUnauthorized(): void
    {
        $this->expectException(UnauthorizedException::class);

        $guard = $this->createMock(Guard::class);
        $guard->expects($this->once())->method('user')->willReturn(true);

        $gate = $this->createMock(Gate::class);
        $gate->expects($this->once())->method('check')->willReturn(false);

        $decider = new AccessRoleDecider(
            'anonymous',
            'user',
            $guard,
            $gate
        );

        $decider->decideAccessRole($this->createMock(ServerRequestInterface::class));
    }
}