<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

use Hasura\ApiClient\Client;
use Hasura\Metadata\Command\ApplyMetadata;
use Hasura\Metadata\Command\ClearMetadata;
use Hasura\Metadata\Command\DropInconsistentMetadata;
use Hasura\Metadata\Command\ExportMetadata;
use Hasura\Metadata\Command\GetInconsistentMetadata;
use Hasura\Metadata\Command\InitConfig;
use Hasura\Metadata\Command\ReloadMetadata;
use Hasura\Metadata\Manager;
use Hasura\Metadata\ManagerInterface;
use Hasura\Metadata\YamlOperator;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

(new class() {
    private ArgvInput $input;

    private ConsoleOutput $output;

    private SymfonyStyle $symfonyStyle;

    private Filesystem $filesystem;

    public function __construct()
    {
        $possibleAutoloadPaths = [
            // monorepo
            __DIR__ . '/../../../vendor/autoload.php',
            // after split package
            __DIR__ . '/../vendor/autoload.php',
            // dependency
            __DIR__ . '/../../../autoload.php',
        ];

        foreach ($possibleAutoloadPaths as $possibleAutoloadPath) {
            if (\file_exists($possibleAutoloadPath)) {
                require_once $possibleAutoloadPath;
                break;
            }
        }

        $this->input = new ArgvInput();
        $this->output = new ConsoleOutput();
        $this->symfonyStyle = new SymfonyStyle($this->input, $this->output);
        $this->filesystem = new Filesystem();
    }

    public function run(): void
    {
        $app = new Application('Hasura Metadata');
        $app->addCommands($this->getCommands());
        $app->run($this->input, $this->output);
    }

    private function getCommands(): array
    {
        if ('init-config' === $this->input->getFirstArgument()) {
            return [new InitConfig($this->filesystem)];
        }

        $manager = $this->getManager();

        return [
            new ApplyMetadata($manager),
            new ClearMetadata($manager),
            new DropInconsistentMetadata($manager),
            new ExportMetadata($manager),
            new GetInconsistentMetadata($manager),
            new InitConfig($this->filesystem),
            new ReloadMetadata($manager),
        ];
    }

    private function getManager(): ManagerInterface
    {
        $config = $this->getConfig();
        $apiClient = new Client($config['baseUri'], $config['adminSecret']);
        $yamlOperator = new YamlOperator($this->filesystem);

        return new Manager($apiClient, $config['metadataPath'], $yamlOperator);
    }

    private function getConfig(): array
    {
        $config = [];
        $file = getcwd() . '/hasura.php';

        if ($this->input->hasParameterOption(['-c', '--config'])) {
            $configFile = $this->input->getParameterOption(['-c', '--config']);

            if (\is_string($configFile)) {
                $file = $configFile;
            }
        }

        if ($this->filesystem->exists($file)) {
            $config = require_once $file;

            if (!\is_array($config)) {
                throw new \LogicException(
                    sprintf('Config file: `%s` should return an array but got `%s`', $file, gettype($config))
                );
            }
        }

        $baseUri = $config['baseUri'] ?? $_ENV['HASURA_BASE_URI'] ?? $_SERVER['HASURA_BASE_URI'] ?? null;
        $adminSecret = $config['adminSecret'] ?? $_ENV['HASURA_ADMIN_SECRET'] ?? $_SERVER['HASURA_ADMIN_SECRET'] ?? null;
        $metadataPath = $config['metadata']['path'] ?? $_ENV['HASURA_METADATA_PATH'] ?? $_SERVER['HASURA_METADATA_PATH'] ?? null;
        $hintMessage = '(Use `init-config` command to generate config file).';

        if (null === $baseUri) {
            $this->symfonyStyle->warning(
                sprintf(
                    'You should be config Hasura base uri via `%s` config file or `HASURA_BASE_URI` env. %s',
                    $file,
                    $hintMessage
                )
            );

            exit(1);
        }

        if (null === $metadataPath) {
            $this->symfonyStyle->warning(
                sprintf(
                    'You should be config Hasura metadata path where to store metadata files via `%s` config file or `HASURA_METADATA_PATH` env. %s',
                    $file,
                    $hintMessage
                )
            );

            exit(1);
        }

        return [
            'baseUri' => $baseUri,
            'adminSecret' => $adminSecret,
            'metadataPath' => $metadataPath,
        ];
    }
})->run();
