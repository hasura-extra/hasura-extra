<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\GraphQLite;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use TheCodingMachine\GraphQLite\Security\AuthorizationServiceInterface;

final class AuthorizationService implements AuthorizationServiceInterface
{
    public function __construct(private AuthorizationCheckerInterface $authorizationChecker)
    {
    }

    public function isAllowed(string $right, $subject = null): bool
    {
        return $this->authorizationChecker->isGranted($right, $subject);
    }
}