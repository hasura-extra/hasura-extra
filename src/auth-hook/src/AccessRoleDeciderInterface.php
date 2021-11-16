<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\AuthHook;

use Psr\Http\Message\ServerRequestInterface;

/**
 * An interface implements by classes decide access role for current request.
 */
interface AccessRoleDeciderInterface
{
    /**
     * @param ServerRequestInterface $request current request.
     * @return string access role of current request (x-hasura-role).
     * @throws UnauthorizedException in case current request's unauthorized. (ex: user had request unauthorized role)
     */
    public function decideAccessRole(ServerRequestInterface $request): string;
}