<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Command;

use Hasura\GraphQLiteBridge\RemoteSchemaInterface;
use Hasura\GraphQLiteBridge\RemoteSchemaProcessorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class PersistRemoteSchema extends Command
{
    public function __construct(private RemoteSchemaInterface $remoteSchema, private iterable $processors)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $symfonyStyle = new SymfonyStyle($input, $output);

        $symfonyStyle->section(
            sprintf('Persisting Hasura remote schema: %s...', $this->remoteSchema->getName())
        );

        foreach ($this->processors as $processor) {
            /** @var RemoteSchemaProcessorInterface $processor */
            $processor->process($this->remoteSchema);
        }

        $symfonyStyle->success(
            sprintf('Congratulation! Remote schema: %s\'s persisted!', $this->remoteSchema->getName())
        );

        return 0;
    }
}