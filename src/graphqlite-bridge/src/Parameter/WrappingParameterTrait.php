<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Parameter;

use TheCodingMachine\GraphQLite\Parameters\ParameterInterface;

trait WrappingParameterTrait
{
    private ParameterInterface $parameter;

    public function getWrappedParameter(bool $recurse = false): ParameterInterface
    {
        $parameter = $this->parameter;

        while ($recurse && $parameter instanceof WrappingParameterInterface) {
            $parameter = $parameter->getWrappedParameter(true);
        }

        return $parameter;
    }
}
