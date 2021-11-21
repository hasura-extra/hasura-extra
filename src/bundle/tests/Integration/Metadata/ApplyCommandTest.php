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

final class ApplyCommandTest extends ConsoleTestCase
{
    public function testApply()
    {
        $command = $this->getCommand('hasura:metadata:apply');
        $tester = new CommandTester($command);

        $tester->execute([]);

        $this->assertStringContainsString('[ERROR] Not found metadata files.', $tester->getDisplay());
    }
}
