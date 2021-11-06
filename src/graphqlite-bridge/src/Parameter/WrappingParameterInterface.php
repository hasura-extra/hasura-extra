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

/**
 * This interface implements by parameter will wrap another parameter (decorating).
 */
interface WrappingParameterInterface extends ParameterInterface
{
    /**
     * @return ParameterInterface wrapped.
     */
    public function getWrappedParameter(bool $recurse = false): ParameterInterface;
}
