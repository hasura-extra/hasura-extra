<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\SailorBridge\Tests\Command;

use Hasura\SailorBridge\Command\InitConfig;
use Hasura\SailorBridge\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class InitConfigTest extends TestCase
{
    private const WORKING_DIR = __DIR__ . '/../.init_config';

    protected function setUp(): void
    {
        parent::setUp();

        $this->filesystem->mkdir(self::WORKING_DIR);
        chdir(self::WORKING_DIR);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->filesystem->remove(self::WORKING_DIR);
    }

    public function testInitConfig(): void
    {
        $configFile = self::WORKING_DIR . '/hasura.php';

        $this->assertFileDoesNotExist($configFile);

        $tester = new CommandTester(new InitConfig($this->filesystem));
        $tester->execute([]);

        $this->assertFileEquals(__DIR__.'/../../hasura.php.dist', $configFile);
        $this->assertStringContainsString('"hasura.php" configuration file generated.', $tester->getDisplay());

        $tester->execute([]);
        $this->assertStringContainsString('The "hasura.php" configuration file already exists.', $tester->getDisplay());
    }
}