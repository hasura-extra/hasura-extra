<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\SailorBridge;

use Spawnia\Sailor\Client as SailorClientInterface;
use Spawnia\Sailor\EndpointConfig as AbstractEndpointConfig;

final class EndpointConfig extends AbstractEndpointConfig
{
    public function __construct(
        private SailorClientInterface $client,
        private string $executorNamespace,
        private string $targetPath,
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
        return $this->targetPath;
    }

    public function searchPath(): string
    {
        return $this->querySpecPath;
    }

    public function schemaPath(): string
    {
        return $this->schemaPath;
    }
}