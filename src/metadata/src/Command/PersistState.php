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

final class PersistState extends BaseCommand
{
    protected static $defaultName = 'persist-state';
    protected static $defaultDescription = 'Persist application state with Hasura.';

    public function __construct(ManagerInterface $manager, private StateProcessorInterface $processor)
    {
        parent::__construct($manager);
    }

    protected function configure()
    {
        $this->addOption(
            'allow-inconsistent',
            mode: InputOption::VALUE_NONE,
            description: 'Allow inconsistent after process'
        );
    }

    protected function doExecute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->section('Persisting application state to Hasura...');

        $this->processor->process($this->metadataManager, $input->getOption('allow-inconsistent'));
        $this->io->success('Persist application states to Hasura successfully!');

        return self::SUCCESS;
    }
}
