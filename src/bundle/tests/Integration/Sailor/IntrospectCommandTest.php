<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Integration\Sailor;

use Hasura\Bundle\Tests\Integration\ConsoleTestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class IntrospectCommandTest extends ConsoleTestCase
{
    use SailorPathTrait;

    public function testIntrospect(): void
    {
        $this->assertFileDoesNotExist($this->schemaPath);

        $command = $this->getCommand('hasura:sailor:introspect');

        $tester = new CommandTester($command);
        $tester->execute([]);

        $this->assertFileExists($this->schemaPath);
        $this->assertStringContainsString('[OK] Introspection successfully!', $tester->getDisplay());
    }
}