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

/**
 * Input parameter will help to change argument name difference with controller argument name.
 *
 * Note: Only use once arg naming, if use more than once, [[\TheCodingMachine\GraphQLite\GraphQLRuntimeException]] should be throw.
 */
interface ArgNamingParameterInterface extends InputTypeParameterInterface
{
    /**
     * @return string controller argument name.
     */
    public function getName(): string;

    /**
     * @return string argument name.
     */
    public function getArgName(): string;
}
