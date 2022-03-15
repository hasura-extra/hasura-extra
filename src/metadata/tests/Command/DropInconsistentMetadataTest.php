<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata\Tests\Command;

use Hasura\Metadata\Command\DropInconsistentMetadata;
use Hasura\Metadata\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class DropInconsistentMetadataTest extends TestCase
{
    public function testDropInconsistentMetadata(): void
    {
        $this->putInconsistentTable();
        $this->assertTrue(in_array('inconsistent_table', $this->getCurrentTables(), true));

        $tester = new CommandTester(new DropInconsistentMetadata($this->manager));
        $tester->execute([]);
        $this->assertStringContainsString('Dropping...', $tester->getDisplay());
        $this->assertStringContainsString('Drop inconsistencies in Hasura metadata successfully!', $tester->getDisplay());

        $this->assertFalse(in_array('inconsistent_table', $this->getCurrentTables(), true));
    }

    private function getCurrentTables(): array
    {
        $metadata = $this->getCurrentMetadata();

        return array_column(
            array_column($metadata['sources'][0]['tables'], 'table'),
            'name'
        );
    }
}
