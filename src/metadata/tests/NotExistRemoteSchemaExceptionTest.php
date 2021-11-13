<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata\Tests;

use Hasura\Metadata\NotExistRemoteSchemaException;

final class NotExistRemoteSchemaExceptionTest extends TestCase
{
    public function testConstructor(): void
    {
        $previous = new \RuntimeException();
        $exception = new NotExistRemoteSchemaException('0', 1,  $previous);

        $this->assertSame('0', $exception->getRemoteSchemaName());
        $this->assertSame('Remote schema name: `0` not exist, Did you forget to add it?', $exception->getMessage());
        $this->assertSame(1, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}