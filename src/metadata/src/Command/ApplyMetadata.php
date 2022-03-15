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
use Hasura\Metadata\LanguagePool;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

final class ApplyMetadata extends BaseCommand
{
    protected static $defaultName = LanguagePool::COMMAND_APPLY;

    protected static $defaultDescription = LanguagePool::COMMAND_APPLY_DESCRIPTION;

    protected function configure()
    {
        parent::configure();

        $this->addOption(
            LanguagePool::OPTION_ALLOW_INCONSISTENT,
            mode: InputOption::VALUE_NONE,
            description: LanguagePool::OPTION_ALLOW_INCONSISTENT_DESCRIPTION
        );

        $this->addOption(
            LanguagePool::OPTION_ALLOW_NO_METADATA,
            mode: InputOption::VALUE_NONE,
            description: LanguagePool::OPTION_ALLOW_NO_METADATA_DESCRIPTION
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->section(LanguagePool::COMMAND_APPLY_PROCESSING);

        try {
            $this->metadataManager->apply($input->getOption(LanguagePool::OPTION_ALLOW_INCONSISTENT));

            $this->io->success(LanguagePool::STATUS_DONE);

            return self::SUCCESS;
        } catch (HttpExceptionInterface $exception) {
            $this->io->error($exception->getResponse()->getContent(false));
            $this->io->error(LanguagePool::INFO_CHECK_SERVER_CONFIG);
        } catch (EmptyMetadataException) {
            if (!$input->getOption(LanguagePool::OPTION_ALLOW_NO_METADATA)) {
                $this->io->error(LanguagePool::INFO_NOT_FOUND_METADATA_FILE);
            } else {
                $this->io->warning(LanguagePool::INFO_NO_METADATA_APPLY);

                return self::SUCCESS;
            }
        }

        return self::FAILURE;
    }
}
