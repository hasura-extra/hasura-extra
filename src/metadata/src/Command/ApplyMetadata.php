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

final class ApplyMetadata extends BaseCommand
{
    protected static $defaultName = 'apply';
    protected static $defaultDescription = 'Apply Hasura metadata';

    protected function configure()
    {
        parent::configure();

        $this->addOption(
            'allow-inconsistent',
            mode: InputOption::VALUE_NONE,
            description: 'Allow inconsistent when apply metadata files.'
        );

        $this->addOption(
            'allow-no-metadata',
            mode: InputOption::VALUE_NONE,
            description: 'Allow no metadata files.'
        );
    }

    protected function doExecute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->section('Applying...');

        try {
            $this->metadataManager->apply($input->getOption('allow-inconsistent'));
            $this->io->success('Apply Hasura metadata successfully!');

            return self::SUCCESS;
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
