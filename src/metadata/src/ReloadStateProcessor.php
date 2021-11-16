<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata;

final class ReloadStateProcessor implements StateProcessorInterface
{
    public function process(ManagerInterface $manager, bool $allowInconsistent = false): void
    {
        $manager->reload(true, true);
    }
}