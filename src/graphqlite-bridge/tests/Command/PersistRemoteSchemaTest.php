<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Tests\Command;

use Hasura\GraphQLiteBridge\Command\PersistRemoteSchema;
use Hasura\GraphQLiteBridge\RemoteSchema;
use Hasura\GraphQLiteBridge\RemoteSchemaProcessorInterface;
use Hasura\GraphQLiteBridge\Tests\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class PersistRemoteSchemaTest extends TestCase
{
    public function testPersistRemoteSchema(): void
    {
        $command = new PersistRemoteSchema(new RemoteSchema('apache'), [$this->createMockProcessor()]);
        $tester = new CommandTester($command);
        $tester->execute([]);

        $this->assertStringContainsString('Persisting Hasura remote schema', $tester->getDisplay());
        $this->assertStringContainsString('Congratulation!', $tester->getDisplay());
    }

    private function createMockProcessor(): RemoteSchemaProcessorInterface
    {
        $mock = $this->createMock(RemoteSchemaProcessorInterface::class);
        $mock->expects($this->once())->method('process');

        return $mock;
    }
}