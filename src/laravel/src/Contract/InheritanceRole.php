<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Contract;

/*
 * This interface implements by classes defines inherited roles.
 */
interface InheritanceRole
{
    /**
     * @param array $roles want to get inherited roles.
     * @return array reachable roles includes roles given.
     */
    public function getReachableRoleNames(array $roles): array;
}