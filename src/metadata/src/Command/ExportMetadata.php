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
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

final class ExportMetadata extends BaseCommand
{
    protected static $defaultName = 'export';
    protected static $defaultDescription = 'Export Hasura metadata';

    protected const OPTION_FORCE = 'force';

    protected function configure()
    {
        parent::configure();

        $this->addOption(
            self::OPTION_FORCE,
            mode: InputOption::VALUE_NONE,
            description: 'Force metadata files sync with current metadata.'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->section('Exporting...');

        try {
            $this->metadataManager->export($input->getOption(self::OPTION_FORCE));
            $this->informProcessingDone();

            return self::SUCCESS;
        } catch (HttpExceptionInterface $exception) {
            $this->io->error($exception->getResponse()->getContent(false));
            $this->io->error(self::INFO_CHECK_SERVER_CONFIG);
        }

        return self::FAILURE;
    }
}
