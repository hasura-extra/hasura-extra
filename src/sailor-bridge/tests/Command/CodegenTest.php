<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\SailorBridge\Tests\Command;

use Hasura\SailorBridge\Command\Codegen;
use Hasura\SailorBridge\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class CodegenTest extends TestCase
{
    public function testCodegen(): void
    {
        $tester = new CommandTester(new Codegen());
        $tester->execute([]);
        $this->assertStringContainsString('Generating...', $tester->getDisplay());
        $this->assertStringContainsString('Generated successfully!', $tester->getDisplay());
        $this->assertFileExists(self::CODEGEN_PATH . '/Article.php');
    }
}