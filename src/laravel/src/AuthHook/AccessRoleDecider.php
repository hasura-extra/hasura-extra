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
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Psr\Http\Message\ServerRequestInterface;

final class AccessRoleDecider implements AccessRoleDeciderInterface
{
    public function __construct(
        private string $anonymousRole,
        private string $defaultRole,
        private GateContract $gate,
    ) {
    }

    public function decideAccessRole(ServerRequestInterface $request): string
    {
        if ($this->gate->check($this->anonymousRole)) {
            return $this->anonymousRole;
        }

        $requestedRole = $request->getHeader('x-hasura-role')[0] ?? $this->defaultRole;

        if (false === $this->gate->check($requestedRole)) {
            throw new UnauthorizedException(sprintf('Unauthorized to access with role: `%s`', $requestedRole));
        }

        return $requestedRole;
    }
}