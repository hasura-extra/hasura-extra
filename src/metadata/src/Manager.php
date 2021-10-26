<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata;

use Hasura\ApiClient\Client;

final class Manager implements ManagerInterface
{
    public function __construct(
        private Client $apiClient,
        private string $metadataPath,
        private OperatorInterface $operator,
    ) {
    }

    public function export(bool $force): void
    {
        $data = $this->apiClient->metadata()->query(
            'export_metadata',
            [],
            2
        );
        $metadata = MetadataUtils::normalizeMetadata($data['metadata']);

        $this->operator->export($metadata, $this->metadataPath, $force);
    }

    public function apply(bool $allowInconsistent = false): void
    {
        $metadata = $this->operator->load($this->metadataPath);

        if (empty($metadata)) {
            throw new EmptyMetadataException('Should not apply empty metadata.');
        }

        $this->apiClient->metadata()->query(
            'replace_metadata',
            [
                'metadata' => $metadata,
                'allow_inconsistent_metadata' => $allowInconsistent,
            ],
            2
        );
    }

    public function reload(bool $reloadRemoteSchemas = true, bool $reloadSources = true): void
    {
        $this->apiClient->metadata()->query(
            'reload_metadata',
            [
                'reload_remote_schemas' => $reloadRemoteSchemas,
                'reload_sources' => $reloadSources,
            ]
        );
    }

    public function clear(): void
    {
        $this->apiClient->metadata()->query(
            'clear_metadata',
            []
        );
    }

    public function getInconsistentMetadata(): array
    {
        return $this->apiClient->metadata()->query(
            'get_inconsistent_metadata',
            []
        );
    }

    public function dropInconsistentMetadata(): void
    {
        $this->apiClient->metadata()->query(
            'drop_inconsistent_metadata',
            []
        );
    }
}
