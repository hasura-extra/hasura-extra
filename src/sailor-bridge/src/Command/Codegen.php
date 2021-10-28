<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\SailorBridge\Command;

use Spawnia\Sailor\Console\CodegenCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class Codegen extends CodegenCommand
{
    protected static $defaultName = 'codegen';

    protected static $defaultDescription = 'Generate query executor classes from your graphql query spec';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        $symfonyStyle->section('Generating...');

        ob_start();
        $statusCode = parent::execute($input, $output);
        ob_end_clean();

        $symfonyStyle->success('Generated successfully!');

        return $statusCode;
    }
}