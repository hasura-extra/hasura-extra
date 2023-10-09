<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Integration\Metadata;

use Hasura\Bundle\Tests\Integration\ConsoleTestCase;
use Hasura\Bundle\Tests\Metadata\BackupMetadataTrait;
use Symfony\Component\Console\Tester\CommandTester;

final class ClearCommandTest extends ConsoleTestCase
{
    use BackupMetadataTrait;

    public function testClear()
    {
        $data = $this->client->metadata()->query('export_metadata', [], 2);

        $this->assertNotEmpty($data['metadata']['sources'][0]['tables']);

        $command = $this->getCommand('hasura:metadata:clear');
        $tester = new CommandTester($command);

        $tester->execute([]);

        $data = $this->client->metadata()->query('export_metadata', [], 2);

        $this->assertEmpty($data['metadata']['sources'][0]['tables']);

        $this->assertStringContainsString('[OK] Clear Hasura metadata successfully!', $tester->getDisplay());
    }
}
