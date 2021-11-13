<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata\Tests;

use Hasura\Metadata\MetadataUtils;

final class MetadataUtilsTest extends TestCase
{
    public function testNormalizeMetadata()
    {
        $data = $this->client->metadata()->query('export_metadata', [], 2);
        $metadata = $data['metadata'];
        $remoteSchemas = array_column($metadata['remote_schemas'], null, 'name');

        $this->assertArrayHasKey('countries', $remoteSchemas);
        $this->assertIsArray($remoteSchemas['countries']['definition']['customization']['type_names']['mapping']);
        $this->assertEmpty($remoteSchemas['countries']['definition']['customization']['type_names']['mapping']);

        $metadataNormalized = MetadataUtils::normalizeMetadata($metadata);
        $remoteSchemas = array_column($metadataNormalized['remote_schemas'], null, 'name');

        $this->assertArrayHasKey('countries', $remoteSchemas);
        $this->assertNotSame($metadataNormalized, $metadata);
        $this->assertIsObject(
            $remoteSchemas['countries']['definition']['customization']['type_names']['mapping']
        );
    }
}
