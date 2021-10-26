<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata\Tests\Command;

use Hasura\Metadata\Command\GetInconsistentMetadata;
use Hasura\Metadata\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class GetInconsistentMetadataTest extends TestCase
{
    public function testGetInconsistentMetadata(): void
    {
        $this->putInconsistentTable();

        $tester = new CommandTester(new GetInconsistentMetadata($this->manager));
        $tester->execute([]);

        $this->assertStringContainsString('Getting...', $tester->getDisplay());
        $this->assertStringContainsString(
            'Inconsistent object: no such table/view exists in source: "inconsistent_table"',
            $tester->getDisplay()
        );
    }

    public function testGetConsistentMetadata(): void
    {
        $tester = new CommandTester(new GetInconsistentMetadata($this->manager));
        $tester->execute([]);

        $this->assertStringContainsString('Getting...', $tester->getDisplay());
        $this->assertStringContainsString(
            'Current metadata is consistent with database sources!',
            $tester->getDisplay()
        );
    }
}
