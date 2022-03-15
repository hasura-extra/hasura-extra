<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata\Command;

use Hasura\Metadata\EmptyMetadataException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

final class ApplyMetadata extends BaseCommand
{
    protected static $defaultName = 'apply';
    protected static $defaultDescription = 'Apply Hasura metadata';

    protected const OPTION_ALLOW_INCONSISTENT = 'allow-inconsistent';
    protected const OPTION_ALLOW_NO_METADATA = 'allow-no-metadata';

    protected function configure()
    {
        parent::configure();

        $this->addOption(
            self::OPTION_ALLOW_INCONSISTENT,
            mode: InputOption::VALUE_NONE,
            description: 'Allow inconsistent when apply metadata files.'
        );

        $this->addOption(
            self::OPTION_ALLOW_NO_METADATA,
            mode: InputOption::VALUE_NONE,
            description: 'Allow no metadata files.'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->section('Applying...');

        try {
            $this->metadataManager->apply($input->getOption(self::OPTION_ALLOW_INCONSISTENT));
            $this->informProcessingDone();

            return self::SUCCESS;
        } catch (HttpExceptionInterface $exception) {
            $this->io->error($exception->getResponse()->getContent(false));
            $this->io->error(self::INFO_CHECK_SERVER_CONFIG);
        } catch (EmptyMetadataException) {
            if (!$input->getOption('allow-no-metadata')) {
                $this->io->error('Not found metadata files.');
            } else {
                $this->io->warning('No metadata files to apply.');

                return self::SUCCESS;
            }
        }

        return self::FAILURE;
    }
}
