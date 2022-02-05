<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\ApiClient;

final class MetadataApi extends AbstractApi
{
    protected function apiPath(): string
    {
        return '/v1/metadata';
    }

    public function query(string $type, array $args, int $version = null, int $resourceVersion = null): array
    {
        $payload = compact('type', 'args');

        if (null !== $version) {
            $payload['version'] = $version;
        }

        if (null !== $resourceVersion) {
            $payload['resource_version'] = $resourceVersion;
        }

        return $this->request('POST', [
            'json' => $payload,
        ])->toArray();
    }
}
