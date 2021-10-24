<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class GetInconsistentMetadata extends BaseCommand
{
    protected static $defaultName = 'get-inconsistent';

    protected static $defaultDescription = 'Get inconsistent Hasura metadata';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io->section('Getting...');

        $data = $this->metadataManager->getInconsistentMetadata();

        if (true === $data['is_consistent']) {
            $this->io->success('Current metadata is consistent with database sources!');

            return 0;
        }

        $this->io->table(
            ['TYPE', 'NAME', 'REASON'],
            array_map(
                fn ($item) => [$item['type'], $item['name'], $item['reason']],
                $data['inconsistent_objects']
            )
        );

        return 1;
    }
}
