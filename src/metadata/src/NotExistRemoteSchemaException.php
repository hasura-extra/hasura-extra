<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata;

use Throwable;

final class NotExistRemoteSchemaException extends \RuntimeException implements ExceptionInterface
{
    public function __construct(
        private string $remoteSchemaName,
        $code = 0,
        Throwable $previous = null
    ) {
        $message = sprintf('Remote schema: `%s` not exist, Did you forget to add it?', $this->remoteSchemaName);

        parent::__construct($message, $code, $previous);
    }

    public function getRemoteSchemaName(): string
    {
        return $this->remoteSchemaName;
    }
}
