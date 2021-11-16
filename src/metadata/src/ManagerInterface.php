<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata;

/**
 * This interface implement by classes to help you manage Hasura metadata.
 */
interface ManagerInterface
{
    /**
     * Export metadata to files
     *
     * @param bool $force export, delete old metadata before export
     */
    public function export(bool $force): void;

    /**
     * Export metadata to PHP array
     */
    public function exportToArray(): array;

    /**
     * Apply metadata from exported files
     *
     * @param bool $allowInconsistent current metadata with database sources
     */
    public function apply(bool $allowInconsistent = false): void;

    /**
     * Apply metadata from array
     *
     * @param array $metadata to apply
     * @param bool $allowInconsistent current metadata with database sources
     */
    public function applyFromArray(array $metadata, bool $allowInconsistent = false): void;

    /**
     * @param bool $reloadRemoteSchemas whether reload remote schemas or not
     * @param bool $reloadSources whether reload sources or not
     */
    public function reload(bool $reloadRemoteSchemas = true, bool $reloadSources = true): void;

    /**
     * Clear metadata
     */
    public function clear(): void;

    /**
     * Return an array inconsistent metadata information.
     *
     * @link https://hasura.io/docs/latest/graphql/core/api-reference/metadata-api/manage-metadata.html#get-inconsistent-metadata
     */
    public function getInconsistentMetadata(): array;

    /**
     * Drop inconsistent metadata.
     */
    public function dropInconsistentMetadata(): void;
}
