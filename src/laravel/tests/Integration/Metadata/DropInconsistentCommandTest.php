<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Integration\Metadata;

use Hasura\Metadata\ManagerInterface;

final class DropInconsistentCommandTest extends MetadataTestCase
{
    public function testDropInconsistent(): void
    {
        $this->putInconsistentTable();

        $inconsistentMetadata = $this->app[ManagerInterface::class]->getInconsistentMetadata();
        $this->assertFalse($inconsistentMetadata['is_consistent']);

        $tester = $this->artisan('hasura:metadata:drop-inconsistent');
        $tester->assertSuccessful();
        $tester->run();

        $inconsistentMetadata = $this->app[ManagerInterface::class]->getInconsistentMetadata();
        $this->assertTrue($inconsistentMetadata['is_consistent']);
    }
}
