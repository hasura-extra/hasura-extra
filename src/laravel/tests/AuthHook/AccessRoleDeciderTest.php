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
        $gate = $this->createMock(Gate::class);
        $gate->expects($this->once())->method('check')->willReturnCallback(
            static fn (string $role) => $role === 'anonymous'
        );

        $decider = new AccessRoleDecider(
            'anonymous',
            'user',
            $gate
        );

        $role = $decider->decideAccessRole($this->createMock(ServerRequestInterface::class));

        $this->assertSame('anonymous', $role);
    }

    public function testDecideDefaultRoleForUser(): void
    {
        $gate = $this->createMock(Gate::class);
        $gate
            ->expects($this->exactly(2))
            ->method('check')
            ->willReturn(false, static fn (string $role) => $role === 'user');

        $decider = new AccessRoleDecider(
            'anonymous',
            'user',
            $gate
        );

        $role = $decider->decideAccessRole($this->createMock(ServerRequestInterface::class));

        $this->assertSame('user', $role);
    }

    public function testDecideRequestedRoleForUser(): void
    {
        $gate = $this->createMock(Gate::class);
        $gate
            ->expects($this->exactly(2))
            ->method('check')
            ->willReturn(false, static fn (string $role) => $role === 'admin');

        $serverRequest = $this->createMock(ServerRequestInterface::class);
        $serverRequest->expects($this->once())->method('getHeader')->willReturn(['admin']);

        $decider = new AccessRoleDecider(
            'anonymous',
            'user',
            $gate
        );

        $role = $decider->decideAccessRole($serverRequest);

        $this->assertSame('admin', $role);
    }

    public function testDecideUnauthorized(): void
    {
        $this->expectException(UnauthorizedException::class);

        $gate = $this->createMock(Gate::class);
        $gate
            ->expects($this->exactly(2))
            ->method('check')
            ->willReturn(false, false);

        $decider = new AccessRoleDecider(
            'anonymous',
            'user',
            $gate
        );

        $decider->decideAccessRole($this->createMock(ServerRequestInterface::class));
    }
}
