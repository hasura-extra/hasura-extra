<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata\Command;

use Hasura\Metadata\StateProcessorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

final class PersistState extends Command
{
    protected static $defaultName = 'persist-state';

    protected static $defaultDescription = 'Persist application state with Hasura.';

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

        try {
            foreach ($this->processors as $processor) {
                /** @var StateProcessorInterface $processor */
                $processor->process();
            }
        } catch (ClientExceptionInterface $clientException) {
            $content = $clientException->getResponse()->getContent(false);

            $symfonyStyle->error($content);

            return 1;
        }

        $symfonyStyle->success('Congratulation! Application state persisted with Hasura!');

        return 0;
    }
}