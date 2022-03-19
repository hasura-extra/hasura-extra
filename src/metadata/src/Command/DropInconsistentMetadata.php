<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class DropInconsistentMetadata extends BaseCommand
{
    protected static $defaultName = 'drop-inconsistent';

    protected static $defaultDescription = 'Drop inconsistent Hasura metadata';

    protected function doExecute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->section('Dropping...');

        $this->metadataManager->dropInconsistentMetadata();

        $this->io->success('Drop inconsistencies in Hasura metadata successfully!');

        return self::SUCCESS;
    }
}
