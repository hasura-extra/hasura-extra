<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Command;

use Hasura\GraphQLiteBridge\StateProcessorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class PersistState extends Command
{
    public function __construct(private iterable $processors)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $symfonyStyle = new SymfonyStyle($input, $output);

        $symfonyStyle->section(
            sprintf('Persisting application state to Hasura...')
        );

        foreach ($this->processors as $processor) {
            /** @var StateProcessorInterface $processor */
            $processor->process();
        }

        $symfonyStyle->success('Congratulation! Application state persisted with Hasura!');

        return 0;
    }
}