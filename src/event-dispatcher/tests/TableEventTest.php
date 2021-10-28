<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\EventDispatcher\Tests;

use Hasura\EventDispatcher\TableEvent;
use PHPUnit\Framework\TestCase;

final class TableEventTest extends TestCase
{
    public function testGetters()
    {
        $event = new TableEvent('1', '2', [3], [4], [5], new \DateTimeImmutable());

        $this->assertSame('1', $event->getId());
        $this->assertSame('2', $event->getTriggerName());
        $this->assertSame([3], $event->getTable());
        $this->assertSame([4], $event->getEvent());
        $this->assertSame([5], $event->getDeliveryInfo());
        $this->assertInstanceOf(\DateTimeImmutable::class, $event->getCreatedAt());
    }
}