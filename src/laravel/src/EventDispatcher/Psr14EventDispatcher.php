<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\EventDispatcher;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Events\Dispatcher;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;

final class Psr14EventDispatcher implements EventDispatcherInterface
{
    public function __construct(private DispatcherContract $dispatcher)
    {
    }

    public function dispatch(object $event): void
    {
        if (!$this->dispatcher instanceof Dispatcher) {
            throw new \LogicException(
                sprintf(
                    'Dispatcher should be instance of %s but %s given.',
                    Dispatcher::class,
                    get_class($this->dispatcher)
                )
            );
        }

        $listeners = $this->dispatcher->getListeners($event::class);

        foreach ($listeners as $listener) {
            if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                break;
            }

            $listener($event, [$event]);
        }
    }
}