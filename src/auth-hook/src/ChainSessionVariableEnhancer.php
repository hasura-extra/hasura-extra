<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\AuthHook;

final class ChainSessionVariableEnhancer implements SessionVariableEnhancerInterface
{
    public function __construct(private iterable $enhancers)
    {
    }

    public function enhance(array $sessionVariables): array
    {
        foreach ($this->enhancers as $enhancer) {
            /** @var SessionVariableEnhancerInterface $enhancer */
            $sessionVariables = $enhancer->enhance($sessionVariables);
        }

        return $sessionVariables;
    }
}