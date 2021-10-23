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
 * This interface implements by classes help to export/load metadata.
 */
interface OperatorInterface
{
    /**
     * @param array $metadata need to export to files.
     * @param string $toPath store metadata files.
     * @param bool $force whether delete old metadata before export.
     */
    public function export(array $metadata, string $toPath, bool $force = false): void;

    /**
     * @param string $fromPath store metadata files need to load.
     * @return array metadata
     */
    public function load(string $fromPath): array;
}