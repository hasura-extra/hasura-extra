<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata\Tests;

use Hasura\Metadata\ReloadStateProcessor;

final class ReloadStateProcessorTest extends TestCase
{
    public function testProcess(): void
    {
        $this->expectNotToPerformAssertions();

        $processor = new ReloadStateProcessor();

        $processor->process($this->manager);
    }
}
