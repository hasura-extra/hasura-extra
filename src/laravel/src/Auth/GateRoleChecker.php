<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Auth;

final class GateRoleChecker
{
    public function __construct(
        private string $anonymousRole,
        private InheritanceRole $inheritanceRole
    ) {
    }

    public function check(?object $user, iterable|string $abilities): ?bool
    {
        if (null === $user) {
            return $abilities === $this->anonymousRole ? true : null;
        }

        if (method_exists($user, 'getRoles')) {
            $roles = $this->inheritanceRole->getReachableRoleNames((array)$user->getRoles());

            foreach ($roles as $role) {
                if ($role === $abilities) {
                    return true;
                }
            }
        }

        return null;
    }
}