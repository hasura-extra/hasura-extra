<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

use Hasura\ApiClient\Client;
use Hasura\SailorBridge\Command\Codegen;
use Hasura\SailorBridge\Command\InitConfig;
use Hasura\SailorBridge\Command\Introspect;
use Hasura\SailorBridge\EndpointConfig;
use Hasura\SailorBridge\SailorClient;
use Spawnia\Sailor\Configuration;
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
        $app = new Application('Hasura Sailor');
        $app->addCommands($this->getCommands());
        $app->run($this->input, $this->output);
    }

    private function getCommands(): array
    {
        if ('init-config' === $this->input->getFirstArgument()) {
            return [new InitConfig($this->filesystem)];
        }

        $this->configEndpoint();

        return [
            new Codegen(),
            new Introspect(),
            new InitConfig($this->filesystem),
        ];
    }

    private function configEndpoint(): void
    {
        $config = $this->getConfig();
        $apiClient = new Client($config['baseUri'], $config['adminSecret']);
        $config = new EndpointConfig(
            new SailorClient($apiClient),
            $config['executorNamespace'],
            $config['executorPath'],
            $config['querySpecPath'],
            $config['schemaPath']
        );

        Configuration::setEndpoint('hasura', $config);
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
        $executorNamespace = $config['sailor']['executorNamespace'] ?? $_ENV['SAILOR_EXECUTOR_NAMESPACE'] ?? $_SERVER['SAILOR_EXECUTOR_NAMESPACE'] ?? null;
        $executorPath = $config['sailor']['executorPath'] ?? $_ENV['SAILOR_EXECUTOR_PATH'] ?? $_SERVER['SAILOR_EXECUTOR_PATH'] ?? null;
        $schemaPath = $config['sailor']['schemaPath'] ?? $_ENV['SAILOR_SCHEMA_PATH'] ?? $_SERVER['SAILOR_SCHEMA_PATH'] ?? null;
        $querySpecPath = $config['sailor']['querySpecPath'] ?? $_ENV['SAILOR_QUERY_SPEC_PATH'] ?? $_SERVER['SAILOR_QUERY_SPEC_PATH'] ?? null;
        $hintMessage = '(Use `init-config` command to generate config file).';

        if (null === $executorNamespace) {
            $this->symfonyStyle->warning(
                sprintf(
                    'You should be config Sailor query executor namespace via `%s` (sailor.executorNamespace) config file or `SAILOR_EXECUTOR_NAMESPACE` env. %s',
                    $file,
                    $hintMessage
                )
            );

            exit(1);
        }

        if (null === $executorPath) {
            $this->symfonyStyle->warning(
                sprintf(
                    'You should be config Sailor executor path where to store codegen files via `%s` (sailor.executorPath) config file or `SAILOR_EXECUTOR_PATH` env. %s',
                    $file,
                    $hintMessage
                )
            );

            exit(1);
        }

        if (null === $schemaPath) {
            $this->symfonyStyle->warning(
                sprintf(
                    'You should be config Sailor schema path where to store Hasura schema introspection via `%s` (sailor.schemaPath) config file or `SAILOR_SCHEMA_PATH` env. %s',
                    $file,
                    $hintMessage
                )
            );

            exit(1);
        }

        if (null === $querySpecPath) {
            $this->symfonyStyle->warning(
                sprintf(
                    'You should be config Sailor query spec path where to store graphql query files via `%s` (sailor.querySpecPath) config file or `SAILOR_QUERY_SPEC_PATH` env. %s',
                    $file,
                    $hintMessage
                )
            );

            exit(1);
        }

        return compact(
            'baseUri',
            'adminSecret',
            'executorNamespace',
            'querySpecPath',
            'schemaPath',
            'executorPath'
        );
    }
})->run();
