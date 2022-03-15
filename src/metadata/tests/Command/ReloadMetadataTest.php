<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata\Tests\Command;

use Hasura\Metadata\Command\ReloadMetadata;
use Hasura\Metadata\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class ReloadMetadataTest extends TestCase
{
    public function testReloadMetadata(): void
    {
        $tester = new CommandTester(new ReloadMetadata($this->manager));
        $tester->execute([]);

        $this->assertStringContainsString('Reloading...', $tester->getDisplay());
        $this->assertStringContainsString('Reload Hasura metadata successfully!', $tester->getDisplay());
    }
}
