<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge;

/**
 * This interface implements by classes will process (sync remote schema role permissions, inherited roles, etc...)
 * from your application state to Hasura.
 */
interface StateProcessorInterface
{
    /**
     * process (sync remote schema role permissions, inherited roles, etc...) to Hasura.
     */
    public function process(): void;
}