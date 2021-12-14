<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\AuthHook;

use Hasura\AuthHook\AccessRoleDeciderInterface;
use Hasura\AuthHook\UnauthorizedException;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class AccessRoleDecider implements AccessRoleDeciderInterface
{
    private $tokenStorage;

    private $authorizationChecker;

    public function __construct(
        private string $anonymousRole,
        private string $defaultRole,
        ?TokenStorageInterface $tokenStorage,
        ?AuthorizationCheckerInterface $authorizationChecker,
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function decideAccessRole(ServerRequestInterface $request): string
    {
        if (null === $this->tokenStorage || null === $this->authorizationChecker) {
            throw new \LogicException(
                'The SecurityBundle is not registered in your application. Try running "composer require symfony/security-bundle".'
            );
        }

        if (!$this->tokenStorage?->getToken()?->getUser() instanceof UserInterface) {
            return $this->anonymousRole;
        }

        $requestedRole = $request->getHeader('x-hasura-role')[0] ?? $this->defaultRole;

        if (false === $this->authorizationChecker->isGranted($requestedRole)) {
            throw new UnauthorizedException(sprintf('Unauthorized to access with role: `%s`', $requestedRole));
        }

        return $requestedRole;
    }
}
