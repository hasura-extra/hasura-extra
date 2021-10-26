<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata\Tests\Command;

use Hasura\Metadata\Command\ClearMetadata;
use Hasura\Metadata\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class ClearMetadataTest extends TestCase
{
    public function testClearMetadata(): void
    {
        $tester = new CommandTester(new ClearMetadata($this->manager));
        $tester->execute([]);
        $this->assertStringContainsString('Clearing...', $tester->getDisplay());
        $this->assertStringContainsString('Done!', $tester->getDisplay());

        $data = $this->client->metadata()->query('export_metadata', [], 2);

        $this->assertEmpty($data['metadata']['sources'][0]['tables']);
    }
}
