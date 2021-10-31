<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\SailorBridge\Command;

use Spawnia\Sailor\Codegen\Generator;
use Spawnia\Sailor\Codegen\Writer;
use Spawnia\Sailor\Configuration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class Codegen extends Command
{
    protected static $defaultName = 'codegen';

    protected static $defaultDescription = 'Generate query executor classes from your graphql query spec';

    public function __construct(private string $endpoint = 'hasura')
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        $symfonyStyle->section('Generating...');

        $endpointConfig = Configuration::endpoint($this->endpoint);
        $generator = new Generator($endpointConfig, $this->endpoint);

        $files = $generator->generate();

        $writer = new Writer($endpointConfig);
        $writer->write($files);

        $symfonyStyle->success('Generated successfully!');

        return 0;
    }
}
