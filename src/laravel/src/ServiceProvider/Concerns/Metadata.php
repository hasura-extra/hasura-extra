<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\ServiceProvider\Concerns;

use Hasura\ApiClient\Client;
use Hasura\Laravel\ServiceProvider\HasuraServiceProvider;
use Hasura\Metadata\ChainStateProcessor;
use Hasura\Metadata\Command\ApplyMetadata;
use Hasura\Metadata\Command\ClearMetadata;
use Hasura\Metadata\Command\DropInconsistentMetadata;
use Hasura\Metadata\Command\ExportMetadata;
use Hasura\Metadata\Command\GetInconsistentMetadata;
use Hasura\Metadata\Command\PersistState;
use Hasura\Metadata\Command\ReloadMetadata;
use Hasura\Metadata\InheritedRolesStateProcessor;
use Hasura\Metadata\Manager;
use Hasura\Metadata\ManagerInterface;
use Hasura\Metadata\OperatorInterface;
use Hasura\Metadata\ReloadStateProcessor;
use Hasura\Metadata\RemoteSchema;
use Hasura\Metadata\RemoteSchemaInterface;
use Hasura\Metadata\StateProcessorInterface;
use Hasura\Metadata\YamlOperator;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @mixin HasuraServiceProvider
 */
trait Metadata
{
    private function bootMetadata(): void
    {
        $this->commands(
            [
                ApplyMetadata::class,
                ClearMetadata::class,
                DropInconsistentMetadata::class,
                ExportMetadata::class,
                GetInconsistentMetadata::class,
                PersistState::class,
                ReloadMetadata::class,
            ]
        );
    }

    private function registerMetadata(): void
    {
        $this->app->singleton(
            YamlOperator::class,
            static fn($app) => new YamlOperator(new Filesystem())
        );
        $this->app->bind(OperatorInterface::class, YamlOperator::class);

        $this->app->singleton(
            Manager::class,
            static fn($app) => new Manager(
                $app[Client::class],
                config('hasura.metadata.path'),
                $app[OperatorInterface::class]
            )
        );
        $this->app->bind(ManagerInterface::class, Manager::class);

        $hasRemoteSchema = !is_null(config('hasura.remote_schema_name'));

        if ($hasRemoteSchema) {
            $this->app->singleton(
                RemoteSchema::class,
                static fn($app) => new RemoteSchema(config('hasura.remote_schema_name'))
            );
            $this->app->bind(RemoteSchemaInterface::class, RemoteSchema::class);
        }

        $this->app->singleton(ReloadStateProcessor::class);

        $this->app->singleton(
            InheritedRolesStateProcessor::class,
            static fn($app) => new InheritedRolesStateProcessor(
                config('hasura.auth.inherited_roles'),
                $hasRemoteSchema ? $app[RemoteSchemaInterface::class] : null,
                'schema { query: Query } type Query { _dummy: String! }'
            )
        );

        $this->app->singleton(
            ChainStateProcessor::class,
            static fn($app) => new ChainStateProcessor(
                collect(config('hasura.metadata.state_processors'))->map(
                    static fn(string $class) => $app[$class]
                )
            )
        );
        $this->app->bind(StateProcessorInterface::class, ChainStateProcessor::class);

        $this->app->singleton(
            ApplyMetadata::class,
            static function ($app) {
                $command = new ApplyMetadata($app[ManagerInterface::class]);
                $command->setName('hasura:metadata:apply');

                return $command;
            }
        );

        $this->app->singleton(
            ClearMetadata::class,
            static function ($app) {
                $command = new ClearMetadata($app[ManagerInterface::class]);
                $command->setName('hasura:metadata:clear');

                return $command;
            }
        );

        $this->app->singleton(
            DropInconsistentMetadata::class,
            static function ($app) {
                $command = new DropInconsistentMetadata($app[ManagerInterface::class]);
                $command->setName('hasura:metadata:drop-inconsistent');

                return $command;
            }
        );

        $this->app->singleton(
            ExportMetadata::class,
            static function ($app) {
                $command = new ExportMetadata($app[ManagerInterface::class]);
                $command->setName('hasura:metadata:export');

                return $command;
            }
        );

        $this->app->singleton(
            GetInconsistentMetadata::class,
            static function ($app) {
                $command = new GetInconsistentMetadata($app[ManagerInterface::class]);
                $command->setName('hasura:metadata:get-inconsistent');

                return $command;
            }
        );

        $this->app->singleton(
            PersistState::class,
            static function ($app) {
                $command = new PersistState($app[ManagerInterface::class], $app[StateProcessorInterface::class]);
                $command->setName('hasura:metadata:persist-state');

                return $command;
            }
        );

        $this->app->singleton(
            ReloadMetadata::class,
            static function ($app) {
                $command = new ReloadMetadata($app[ManagerInterface::class]);
                $command->setName('hasura:metadata:reload');

                return $command;
            }
        );
    }
}