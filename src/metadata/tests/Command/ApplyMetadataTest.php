<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata\Tests\Command;

use Hasura\Metadata\Command\ApplyMetadata;
use Hasura\Metadata\Command\ExportMetadata;
use Hasura\Metadata\LanguagePool;
use Hasura\Metadata\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class ApplyMetadataTest extends TestCase
{
    public function testApplyMetadata(): void
    {
        $metadataApi = $this->client->metadata();
        $oldResourceVersion = $metadataApi->query('export_metadata', [], 2)['resource_version'];

        // export yaml files
        $exporter = new CommandTester(new ExportMetadata($this->manager));
        $exporter->execute([
            '--force' => true,
        ]);

        // apply
        $tester = new CommandTester(new ApplyMetadata($this->manager));
        $tester->execute([]);

        $currentResourceVersion = $metadataApi->query('export_metadata', [], 2)['resource_version'];

        $this->assertSame($oldResourceVersion + 1, $currentResourceVersion);
        $this->assertStringContainsString(LanguagePool::COMMAND_APPLY_PROCESSING, $tester->getDisplay());
        $this->assertStringContainsString(LanguagePool::STATUS_DONE, $tester->getDisplay());
    }

    public function testApplyNothing()
    {
        $tester = new CommandTester(new ApplyMetadata($this->manager));
        $tester->execute([]);

        $this->assertSame(1, $tester->getStatusCode());
        $this->assertStringContainsString(LanguagePool::INFO_NOT_FOUND_METADATA_FILE, $tester->getDisplay());

        $tester->execute([
            '--allow-no-metadata' => true,
        ]);
        $this->assertSame(0, $tester->getStatusCode());
        $this->assertStringContainsString(LanguagePool::INFO_NO_METADATA_APPLY, $tester->getDisplay());
    }
}
