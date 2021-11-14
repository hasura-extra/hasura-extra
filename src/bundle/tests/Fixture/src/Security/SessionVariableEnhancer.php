<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Fixture\App\Security;

use Hasura\AuthHook\SessionVariableEnhancerInterface;

final class SessionVariableEnhancer implements SessionVariableEnhancerInterface
{
    public function enhance(array $sessionVariables): array
    {
        $sessionVariables['x-hasura-test'] = 'test';

        return $sessionVariables;
    }
}