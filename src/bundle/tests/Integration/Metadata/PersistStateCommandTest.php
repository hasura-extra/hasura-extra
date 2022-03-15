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

final class PersistStateCommandTest extends ConsoleTestCase
{
    use BackupMetadataTrait;

    public function testPersistState()
    {
        $command = $this->getCommand('hasura:metadata:persist-state');
        $tester = new CommandTester($command);

        $tester->execute([]);

        $this->assertStringContainsString(
            '[OK] Done!',
            $tester->getDisplay()
        );
    }
}
