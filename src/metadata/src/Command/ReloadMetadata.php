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

final class ReloadMetadata extends BaseCommand
{
    protected static $defaultName = 'reload';
    protected static $defaultDescription = 'Reload Hasura metadata';

    protected function configure()
    {
        parent::configure();

        $this->addOption(
            'no-reload-remote-schemas',
            mode: InputOption::VALUE_NONE,
            description: 'No reload remote schemas'
        );
        $this->addOption(
            'no-reload-sources',
            mode: InputOption::VALUE_NONE,
            description: 'No reload sources'
        );
    }

    protected function doExecute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->section('Reloading...');

        $this->metadataManager->reload(
            !$input->getOption('no-reload-remote-schemas'),
            !$input->getOption('no-reload-sources')
        );

        $this->io->success('Reload Hasura metadata successfully!');

        return self::SUCCESS;
    }
}
