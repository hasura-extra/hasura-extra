<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'clear', description: 'Clear Hasura metadata')]
final class ClearMetadata extends BaseCommand
{
    protected function doExecute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->section('Clearing...');

        $this->metadataManager->clear();

        $this->io->success('Clear Hasura metadata successfully!');

        return self::SUCCESS;
    }
}
