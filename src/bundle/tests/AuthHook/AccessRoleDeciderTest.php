<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\AuthHook;

use Hasura\Bundle\AuthHook\AccessRoleDecider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class AccessRoleDeciderTest extends TestCase
{
    public function testDecideAnonymousRole(): void
    {
        $decider = new AccessRoleDecider(
            'anonymous',
            'default',
            $this->createMockTokenStorage(),
            $this->createMockAuthorizationChecker(true, 0)
        );

        $role = $decider->decideAccessRole($this->createMock(ServerRequestInterface::class));

        $this->assertSame('anonymous', $role);
    }

    public function testDecideDefaultRole(): void
    {
        $decider = new AccessRoleDecider(
            'anonymous',
            'default',
            $this->createMockTokenStorage(
                $this->createMockToken(
                    $this->createMock(UserInterface::class)
                )
            ),
            $this->createMockAuthorizationChecker(true, 1)
        );
        $request = $this->createMockServerRequest([]);
        $role = $decider->decideAccessRole($request);

        $this->assertSame('default', $role);
    }

    public function testDecideRequestedRole(): void
    {
        $decider = new AccessRoleDecider(
            'anonymous',
            'default',
            $this->createMockTokenStorage(
                $this->createMockToken(
                    $this->createMock(UserInterface::class)
                )
            ),
            $this->createMockAuthorizationChecker(true, 1)
        );
        $request = $this->createMockServerRequest('test');
        $role = $decider->decideAccessRole($request);

        $this->assertSame('test', $role);
    }

    public function testMissingSecurityBundle(): void
    {
        $this->expectException(\LogicException::class);

        $decider = new AccessRoleDecider(
            'anonymous',
            'default',
            null,
            null
        );

        $decider->decideAccessRole($this->createMock(ServerRequestInterface::class));
    }

    private function createMockAuthorizationChecker(bool $accept, int $callTime = 1): AuthorizationCheckerInterface
    {
        $checker = $this->createMock(AuthorizationCheckerInterface::class);
        $checker
            ->expects($this->exactly($callTime))
            ->method('isGranted')
            ->willReturn($accept);

        return $checker;
    }

    private function createMockTokenStorage(TokenInterface $withToken = null): TokenStorageInterface
    {
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->willReturn($withToken);

        return $tokenStorage;
    }

    private function createMockToken(UserInterface $withUser = null): TokenInterface
    {
        $token = $this->createMock(TokenInterface::class);
        $token
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($withUser);

        return $token;
    }

    private function createMockServerRequest(array|string $withRole): ServerRequestInterface
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getHeader')
            ->willReturn((array)$withRole);

        return $request;
    }
}