<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\SailorBridge\Command;

use Spawnia\Sailor\Configuration;
use Spawnia\Sailor\Introspector;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class Introspect extends Command
{
    protected static $defaultName = 'introspect';

    protected static $defaultDescription = 'Generate schema definition of Hasura via introspect query';

    public function __construct(private string $endpoint = 'hasura')
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        $symfonyStyle->section('Introspecting...');

        $generator = new Introspector(
            Configuration::endpoint($this->endpoint),
            $this->endpoint
        );
        $generator->introspect();

        $symfonyStyle->success('Introspection successfully!');

        return 0;
    }
}
