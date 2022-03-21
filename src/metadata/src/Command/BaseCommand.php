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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;

abstract class BaseCommand extends Command
{
    protected SymfonyStyle $io;

    public function __construct(protected ManagerInterface $metadataManager)
    {
        parent::__construct();
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * @throws ClientExceptionInterface when connections to Hasura have problems.
     */
    abstract protected function doExecute(InputInterface $input, OutputInterface $output);

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $result = $this->doExecute($input, $output);
        } catch (HttpExceptionInterface $exception) {
            $this->io->error($exception->getResponse()->getContent(false));
            $this->io->error('Please check your Hasura server configuration.');

            $result = self::FAILURE;
        }

        return $result;
    }
}
