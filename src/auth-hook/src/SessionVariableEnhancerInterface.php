<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\AuthHook;

/**
 * An interface implements by classes want to enhance session variables of current request.
 */
interface SessionVariableEnhancerInterface
{
    /**
     * @param array $sessionVariables current session variables of current request.
     * @return array session variables of current request.
     */
    public function enhance(array $sessionVariables): array;
}