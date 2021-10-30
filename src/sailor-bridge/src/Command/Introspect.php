<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\SailorBridge\Command;

use Spawnia\Sailor\Console\IntrospectCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class Introspect extends IntrospectCommand
{
    protected static $defaultName = 'introspect';

    protected static $defaultDescription = 'Generate schema definition of Hasura via introspect query';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        $symfonyStyle->section('Introspecting...');

        ob_start();
        $statusCode = parent::execute($input, $output);
        ob_end_clean();

        $symfonyStyle->success('Introspection successfully!');

        return $statusCode;
    }
}