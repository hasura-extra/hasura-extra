<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Parameter;

use TheCodingMachine\GraphQLite\GraphQLRuntimeException;
use TheCodingMachine\GraphQLite\Parameters\InputTypeParameterInterface;

trait ArgNamingMiddlewareTrait
{
    public function assertArgNamingParameterMapped(InputTypeParameterInterface $parameterMapped): void
    {
        $argNamingWrapped = ParameterUtils::getArgNamingParameter($parameterMapped);

        if (null !== $argNamingWrapped) {
            throw new GraphQLRuntimeException(sprintf('Input parameter `%s` have been set arg name!', $argNamingWrapped->getName()));
        }
    }
}
