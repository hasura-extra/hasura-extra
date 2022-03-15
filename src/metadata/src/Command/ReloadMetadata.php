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

    protected const OPTION_NO_RELOAD_REMOTE_SCHEMAS = 'no-reload-remote-schemas';
    protected const OPTION_NO_RELOAD_SOURCES = 'no-reload-sources';

    protected function configure()
    {
        parent::configure();

        $this->addOption(
            self::OPTION_NO_RELOAD_REMOTE_SCHEMAS,
            mode: InputOption::VALUE_NONE,
            description: 'No reload remote schemas'
        );
        $this->addOption(
            self::OPTION_NO_RELOAD_SOURCES,
            mode: InputOption::VALUE_NONE,
            description: 'No reload sources'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->section('Reloading...');

        $this->metadataManager->reload(
            !$input->getOption(self::OPTION_NO_RELOAD_REMOTE_SCHEMAS),
            !$input->getOption(self::OPTION_NO_RELOAD_SOURCES)
        );

        $this->informProcessingDone();

        return self::SUCCESS;
    }
}
