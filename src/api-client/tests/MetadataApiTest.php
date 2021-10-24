<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\ApiClient\Tests;

use PHPUnit\Framework\TestCase;

final class MetadataApiTest extends TestCase
{
    use ClientSetupTrait;

    public function testQueryWithoutVersion(): void
    {
        $data = $this->client->metadata()->query('get_inconsistent_metadata', []);

        $this->assertIsArray($data);
        $this->assertArrayHasKey('is_consistent', $data);
    }

    public function testQueryWithVersion(): void
    {
        $dataVersion1 = $this->client->metadata()->query('export_metadata', [], 1);
        $dataVersion2 = $this->client->metadata()->query('export_metadata', [], 2);

        $this->assertIsArray($dataVersion1);
        $this->assertIsArray($dataVersion2);
        $this->assertNotSame($dataVersion1, $dataVersion2);
    }
}