<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata\Tests\Command;

use Hasura\Metadata\Command\PersistState;
use Hasura\Metadata\ManagerInterface;
use Hasura\Metadata\StateProcessorInterface;
use Hasura\Metadata\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class PersistStateTest extends TestCase
{
    public function testPersistState(): void
    {
        foreach ([true, false] as $allowInconsistent) {
            $command = new PersistState($this->manager, $this->createMockProcessor($allowInconsistent));
            $tester = new CommandTester($command);
            $tester->execute(['--allow-inconsistent' => $allowInconsistent]);

            $this->assertStringContainsString('Persisting application state to Hasura...', $tester->getDisplay());
            $this->assertStringContainsString(
                'Congratulation! Application state persisted with Hasura!',
                $tester->getDisplay()
            );
        }
    }

    private function createMockProcessor(bool $expectAllowInconsistent): StateProcessorInterface
    {
        $mock = $this->createMock(StateProcessorInterface::class);
        $mock
            ->expects($this->once())
            ->method('process')
            ->willReturnCallback(
                fn(ManagerInterface $manager, bool $actualAllowInconsistent) => $this->assertSame(
                    $expectAllowInconsistent,
                    $actualAllowInconsistent
                )
            );

        return $mock;
    }
}