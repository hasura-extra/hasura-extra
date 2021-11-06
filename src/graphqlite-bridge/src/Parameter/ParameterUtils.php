<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Parameter;

use TheCodingMachine\GraphQLite\Parameters\InputTypeParameterInterface;

final class ParameterUtils
{
    public static function getArgNamingParameter(InputTypeParameterInterface $parameter): ?ArgNamingParameterInterface
    {
        while ($parameter instanceof WrappingParameterInterface && !$parameter instanceof ArgNamingParameterInterface) {
            $parameter = $parameter->getWrappedParameter();
        }

        return $parameter instanceof ArgNamingParameterInterface ? $parameter : null;
    }
}
