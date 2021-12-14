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
    private $authorizationChecker;

    public function __construct(?AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    public function isAllowed(string $right, $subject = null): bool
    {
        if (null === $this->authorizationChecker) {
            throw new \LogicException(
                'The SecurityBundle is not registered in your application. Try running "composer require symfony/security-bundle".'
            );
        }

        return $this->authorizationChecker->isGranted($right, $subject);
    }
}
