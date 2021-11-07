<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Tests;

use Hasura\GraphQLiteBridge\NotExistRemoteSchemaException;
use Hasura\GraphQLiteBridge\RuntimeException;

final class NotExistRemoteSchemaExceptionTest extends TestCase
{
    public function testConstructor(): void
    {
        $previous = new RuntimeException();
        $exception = new NotExistRemoteSchemaException('0', '1', 2, $previous);

        $this->assertSame('0', $exception->getRemoteSchemaName());
        $this->assertSame('1', $exception->getMessage());
        $this->assertSame(2, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}