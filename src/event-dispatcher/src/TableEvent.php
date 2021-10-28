<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\EventDispatcher;

use Psr\EventDispatcher\StoppableEventInterface;

final class TableEvent implements StoppableEventInterface
{
    private bool $isPropagationStopped = false;

    public function __construct(
        private string $id,
        private string $triggerName,
        private array $table,
        private array $event,
        private array $deliveryInfo,
        private \DateTimeImmutable $createdAt
    ) {
    }

    public function isPropagationStopped(): bool
    {
        return $this->isPropagationStopped;
    }

    public function stopPropagation(): void
    {
        $this->isPropagationStopped = true;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTriggerName(): string
    {
        return $this->triggerName;
    }

    public function getTable(): array
    {
        return $this->table;
    }

    public function getEvent(): array
    {
        return $this->event;
    }

    public function getDeliveryInfo(): array
    {
        return $this->deliveryInfo;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}