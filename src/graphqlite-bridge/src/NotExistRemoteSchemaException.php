<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge;

use Throwable;

final class NotExistRemoteSchemaException extends RuntimeException
{
    public function __construct(
        private string $remoteSchemaName,
        $message = "",
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getRemoteSchemaName(): string
    {
        return $this->remoteSchemaName;
    }
}