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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class ExportMetadata extends BaseCommand
{
    protected static $defaultName = 'export';
    protected static $defaultDescription = 'Export Hasura metadata';

    protected function configure()
    {
        parent::configure();

        $this->addOption(
            'force',
            mode: InputOption::VALUE_NONE,
            description: 'Force metadata files sync with current metadata.'
        );
    }

    protected function doExecute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->section('Exporting...');

        $this->metadataManager->export($input->getOption('force'));

        $this->io->success('Export Hasura metadata successfully!');

        return self::SUCCESS;
    }
}
