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
 * An interface implements by classes want to enhance session variables of current request.
 */
interface SessionVariableEnhancerInterface
{
    /**
     * @param array $sessionVariables of current request.
     * @param ServerRequestInterface $request current request.
     * @return array session variables enhanced will be use.
     */
    public function enhance(array $sessionVariables, ServerRequestInterface $request): array;
}
