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
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

final class PersistState extends BaseCommand
{
    protected static $defaultName = 'persist-state';

    protected static $defaultDescription = 'Persist application state with Hasura.';

    public function __construct(ManagerInterface $manager, private iterable $processors)
    {
        parent::__construct($manager);
    }

    protected function configure()
    {
        $this->addOption('allow-inconsistent', mode: InputOption::VALUE_NONE, description: 'Allow inconsistent after process');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io->section(
            sprintf('Persisting application state to Hasura...')
        );

        try {
            foreach ($this->processors as $processor) {
                /** @var StateProcessorInterface $processor */
                $processor->process($this->metadataManager, $input->getOption('allow-inconsistent'));
            }
        } catch (ClientExceptionInterface $clientException) {
            $content = $clientException->getResponse()->getContent(false);

            $this->io->error($content);

            return 1;
        }

        $this->io->success('Congratulation! Application state persisted with Hasura!');

        return 0;
    }
}