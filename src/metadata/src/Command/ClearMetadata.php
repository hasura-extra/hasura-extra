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

final class ClearMetadata extends BaseCommand
{
    protected static $defaultName = 'hasura:metadata:clear';

    protected static $defaultDescription = 'Clear Hasura metadata';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io->section('Clearing...');

        $this->metadataManager->clear();

        $this->io->writeln('Done!');

        return 0;
    }
}
