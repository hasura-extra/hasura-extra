<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

final class InitConfig extends Command
{
    protected static $defaultName = 'init-config';

    protected static $defaultDescription = 'Init config file';

    public function __construct(private Filesystem $filesystem)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new SymfonyStyle($input, $output);
        $configFile = getcwd() . '/hasura.php';

        if ($this->filesystem->exists($configFile)) {
            $style->warning('The "hasura.php" configuration file already exists.');

            return 1;
        }

        $this->filesystem->copy(__DIR__ . '/../../hasura.php.dist', $configFile);
        $style->success('"hasura.php" configuration file generated.');

        return 0;
    }
}
