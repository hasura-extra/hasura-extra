<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Fixture\App\Hasura;

use Hasura\AuthHook\SessionVariableEnhancerInterface;
use Psr\Http\Message\ServerRequestInterface;

final class AuthenticatedSessionVariableEnhancer implements SessionVariableEnhancerInterface
{
    public function enhance(array $sessionVariables, ServerRequestInterface $request): array
    {
        $sessionVariables['x-hasura-test'] = 'Laravel';

        return $sessionVariables;
    }
}