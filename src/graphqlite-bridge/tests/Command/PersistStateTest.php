<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Tests\Command;

use Hasura\GraphQLiteBridge\Command\PersistState;
use Hasura\GraphQLiteBridge\StateProcessorInterface;
use Hasura\GraphQLiteBridge\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class PersistStateTest extends TestCase
{
    public function testPersistRemoteSchema(): void
    {
        $command = new PersistState([$this->createMockProcessor()]);
        $tester = new CommandTester($command);
        $tester->execute([]);

        $this->assertStringContainsString('Persisting application state to Hasura...', $tester->getDisplay());
        $this->assertStringContainsString('Congratulation! Application state persisted with Hasura!', $tester->getDisplay());
    }

    private function createMockProcessor(): StateProcessorInterface
    {
        $mock = $this->createMock(StateProcessorInterface::class);
        $mock->expects($this->once())->method('process');

        return $mock;
    }
}