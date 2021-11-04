<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge;

/*
 * This interface implements by classes will process (sync role permissions, etc...) to given Hasura remote schema.
 */
interface RemoteSchemaProcessorInterface
{
    /*
     * process (sync role permissions, etc...) to given Hasura remote schema.
     */
    public function process(RemoteSchemaInterface $remoteSchema): void;
}