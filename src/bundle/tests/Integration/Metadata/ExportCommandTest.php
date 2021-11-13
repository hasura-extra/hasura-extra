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
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;

final class ExportCommandTest extends ConsoleTestCase
{
    protected function tearDown(): void
    {
        (new Filesystem())->remove(self::getContainer()->getParameter('hasura.metadata.path'));

        parent::tearDown();
    }

    public function testExport()
    {
        $command = $this->getCommand('hasura:metadata:export');
        $tester = new CommandTester($command);

        $tester->execute([]);

        $this->assertDirectoryExists(self::getContainer()->getParameter('hasura.metadata.path'));

        $this->assertStringContainsString('[OK] Done!', $tester->getDisplay());
    }
}