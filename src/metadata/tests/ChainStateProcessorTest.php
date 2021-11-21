<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata\Tests;

use Hasura\Metadata\ChainStateProcessor;
use Hasura\Metadata\StateProcessorInterface;

final class ChainStateProcessorTest extends TestCase
{
    public function testConstructor(): void
    {
        $processor1 = $this->createMock(StateProcessorInterface::class);
        $processor2 = $this->createMock(StateProcessorInterface::class);

        $chainProcessor = new ChainStateProcessor([$processor1, $processor2]);

        $this->assertSame([$processor1, $processor2], $chainProcessor->getProcessors());
    }

    public function testProcess(): void
    {
        $processors = [];

        for ($i = 0; $i < 5; $i++) {
            $processor = $processors[] = $this->createMock(StateProcessorInterface::class);
            $processor
                ->expects($this->once())
                ->method('process');
        }

        $this->assertSame(5, count($processors));

        $chainProcessor = new ChainStateProcessor($processors);

        $chainProcessor->process($this->manager);
    }
}
