<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\AuthHook;

use Hasura\AuthHook\AccessRoleDeciderInterface;
use Hasura\AuthHook\UnauthorizedException;
use Illuminate\Contracts\Auth\Guard;
use Psr\Http\Message\ServerRequestInterface;
use Spatie\Permission\Traits\HasRoles;

final class AccessRoleDecider implements AccessRoleDeciderInterface
{
    public function __construct(
        private string $anonymousRole,
        private string $defaultRole,
        private Guard $guard
    ) {
    }

    public function decideAccessRole(ServerRequestInterface $request): string
    {
        $user = $this->guard->user();

        if (null === $user) {
            return $this->anonymousRole;
        }

        if (false === in_array(HasRoles::class, class_uses($user), true)) {
            throw new \LogicException(
                sprintf(
                    'Your %s model should be use %s trait to use auth hook.',
                    $user::class,
                    HasRoles::class
                )
            );
        }

        $requestedRole = $request->getHeader('x-hasura-role')[0] ?? $this->defaultRole;

        if (false === $user->hasRole($requestedRole)) {
            throw new UnauthorizedException(sprintf('Unauthorized to access with role: `%s`', $requestedRole));
        }

        return $requestedRole;
    }
}