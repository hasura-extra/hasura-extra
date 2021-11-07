<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata\Tests;

use Hasura\Metadata\RemoteSchema;

class RemoteSchemaTest extends TestCase
{
    public function testConstructor(): void
    {
        $remoteSchema = new RemoteSchema('test');

        $this->assertSame('test', $remoteSchema->getName());
    }
}