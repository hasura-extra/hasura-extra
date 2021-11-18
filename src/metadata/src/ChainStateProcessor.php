<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata;

final class ChainStateProcessor implements StateProcessorInterface
{
    public function __construct(private iterable $processors)
    {
    }

    public function getProcessors(): iterable
    {
        return $this->processors;
    }

    public function process(ManagerInterface $manager, bool $allowInconsistent = false): void
    {
        foreach ($this->processors as $processor) {
            /** @var StateProcessorInterface $processor */
            $processor->process($manager, $allowInconsistent);
        }
    }
}