<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\SailorBridge;

use GraphQL\Type\Schema;
use Hasura\SailorBridge\Type\DateTimeTypeConfig;
use Hasura\SailorBridge\Type\JsonTypeConfig;
use Hasura\SailorBridge\Type\UuidTypeConfig;
use Spawnia\Sailor\Client as SailorClientInterface;
use Spawnia\Sailor\EndpointConfig as AbstractEndpointConfig;

final class EndpointConfig extends AbstractEndpointConfig
{
    public function __construct(
        private SailorClientInterface $client,
        private string $executorNamespace,
        private string $executorPath,
        private string $querySpecPath,
        private string $schemaPath
    ) {
    }

    public function makeClient(): SailorClientInterface
    {
        return $this->client;
    }

    public function namespace(): string
    {
        return $this->executorNamespace;
    }

    public function targetPath(): string
    {
        return $this->executorPath;
    }

    public function searchPath(): string
    {
        return $this->querySpecPath;
    }

    public function schemaPath(): string
    {
        return $this->schemaPath;
    }

    public function configureTypes(Schema $schema, string $endpointName): array
    {
        return array_merge(
            parent::configureTypes($schema, $endpointName),
            [
                'json' => new JsonTypeConfig(),
                'jsonb' => new JsonTypeConfig(),
                'date' => new DateTimeTypeConfig('date'),
                'timetz' => new DateTimeTypeConfig('timetz'),
                'timestamptz' => new DateTimeTypeConfig('timestamptz'),
                'uuid' => new UuidTypeConfig()
            ]
        );
    }
}
