<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\SailorBridge\Tests\Command;

use Hasura\SailorBridge\Command\Introspect;
use Hasura\SailorBridge\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

class IntrospectTest extends TestCase
{
    public function testIntrospect(): void
    {
        $oldSchema = file_get_contents(self::SCHEMA_PATH);
        $this->filesystem->remove(self::SCHEMA_PATH);
        $tester = new CommandTester(new Introspect());
        $tester->execute([]);

        $this->assertStringContainsString('Introspecting...', $tester->getDisplay());
        $this->assertStringContainsString('Introspection successfully!', $tester->getDisplay());
        $this->assertFileExists(self::SCHEMA_PATH);
        $this->assertSame($oldSchema, file_get_contents(self::SCHEMA_PATH));
    }
}
