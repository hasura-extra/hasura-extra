<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata\Tests\Command;

use Hasura\Metadata\Command\ExportMetadata;
use Hasura\Metadata\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class ExportMetadataTest extends TestCase
{
    public function testExportMetadata(): void
    {
        $tester = new CommandTester(new ExportMetadata($this->manager));
        $tester->execute([]);

        $this->assertStringContainsString('Exporting...', $tester->getDisplay());
        $this->assertStringContainsString('Export Hasura metadata successfully!', $tester->getDisplay());

        $actualFiles = $this->getFilesInDir(self::METADATA_PATH);
        $expectedMetadataPath = __DIR__ . '/../../../../hasura/metadata';
        $expectedFiles = $this->getFilesInDir($expectedMetadataPath);

        $this->assertSame($expectedFiles, $actualFiles, 'Exported files are not expected');

        foreach ($expectedFiles as $expectedFile) {
            $expected = sprintf('%s/%s', $expectedMetadataPath, $expectedFile);
            $actual = sprintf('%s/%s', self::METADATA_PATH, $expectedFile);

            $this->assertFileEquals($expected, $actual);
        }
    }
}
