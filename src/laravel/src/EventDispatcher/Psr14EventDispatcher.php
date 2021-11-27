<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\EventDispatcher;

use Illuminate\Events\Dispatcher;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;

final class Psr14EventDispatcher implements EventDispatcherInterface
{
    public function __construct(private Dispatcher $illuminateDispatcher)
    {
    }

    public function dispatch(object $event): void
    {
        $listeners = $this->illuminateDispatcher->getListeners($event::class);

        foreach ($listeners as $listener) {
            if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                break;
            }

            $listener($event);
        }
    }
}