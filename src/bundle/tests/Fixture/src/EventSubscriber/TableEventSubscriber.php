<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Fixture\App\EventSubscriber;

use Hasura\EventDispatcher\TableEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class TableEventSubscriber implements EventSubscriberInterface
{
    public ?TableEvent $lastEvent = null;

    public static function getSubscribedEvents(): array
    {
        return [
            TableEvent::class => 'onTableEvent'
        ];
    }

    public function onTableEvent(TableEvent $event)
    {
        $this->lastEvent = $event;
    }
}