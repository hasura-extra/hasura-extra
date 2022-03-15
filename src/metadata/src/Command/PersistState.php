<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata\Command;

use Hasura\Metadata\ManagerInterface;
use Hasura\Metadata\StateProcessorInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

final class PersistState extends BaseCommand
{
    protected static $defaultName = 'persist-state';
    protected static $defaultDescription = 'Persist application state with Hasura.';

    protected const OPTION_ALLOW_INCONSISTENT = 'allow-inconsistent';

    public function __construct(ManagerInterface $manager, private StateProcessorInterface $processor)
    {
        parent::__construct($manager);
    }

    protected function configure()
    {
        $this->addOption(
            self::OPTION_ALLOW_INCONSISTENT,
            mode: InputOption::VALUE_NONE,
            description: 'Allow inconsistent after process'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->section('Persisting application state to Hasura...');

        try {
            $this->processor->process($this->metadataManager, $input->getOption(self::OPTION_ALLOW_INCONSISTENT));
        } catch (ClientExceptionInterface $clientException) {
            $content = $clientException->getResponse()->getContent(false);
            $this->io->error($content);

            return self::FAILURE;
        }

        $this->informProcessingDone();

        return self::SUCCESS;
    }
}
