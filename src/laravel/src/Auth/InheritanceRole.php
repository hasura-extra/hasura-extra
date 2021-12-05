<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Auth;

use Hasura\Laravel\Contract\InheritanceRole as InheritanceRoleContract;

final class InheritanceRole implements InheritanceRoleContract
{
    private array $map;

    public function __construct(private array $hierarchy)
    {
        $this->buildRoleMap();
    }

    /*
     * The following methods are derived from code of the Symfony security core package (version 5.3 - role hierarchy)
     *
     * Code subject to the MIT license (https://github.com/symfony/security-core/blob/5.3/LICENSE).
     *
     * Copyright (c) Fabien Potencier <fabien@symfony.com>
     */
    public function getReachableRoleNames(array $roles): array
    {
        $reachableRoles = $roles;

        foreach ($roles as $role) {
            if (!isset($this->map[$role])) {
                continue;
            }

            foreach ($this->map[$role] as $r) {
                $reachableRoles[] = $r;
            }
        }

        return array_values(array_unique($reachableRoles));
    }

    private function buildRoleMap(): void
    {
        $this->map = [];
        foreach ($this->hierarchy as $main => $roles) {
            $this->map[$main] = $roles;
            $visited = [];
            $additionalRoles = $roles;
            while ($role = array_shift($additionalRoles)) {
                if (!isset($this->hierarchy[$role])) {
                    continue;
                }

                $visited[] = $role;

                foreach ($this->hierarchy[$role] as $roleToAdd) {
                    $this->map[$main][] = $roleToAdd;
                }

                foreach (array_diff($this->hierarchy[$role], $visited) as $additionalRole) {
                    $additionalRoles[] = $additionalRole;
                }
            }

            $this->map[$main] = array_unique($this->map[$main]);
        }
    }
}
