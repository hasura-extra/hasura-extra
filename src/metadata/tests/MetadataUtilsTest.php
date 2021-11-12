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

        $this->assertSame('countries', $metadata['remote_schemas'][1]['name']);
        $this->assertIsArray($metadata['remote_schemas'][1]['definition']['customization']['type_names']['mapping']);
        $this->assertEmpty($metadata['remote_schemas'][1]['definition']['customization']['type_names']['mapping']);

        $metadataNormalized = MetadataUtils::normalizeMetadata($metadata);

        $this->assertNotSame($metadataNormalized, $metadata);
        $this->assertIsObject(
            $metadataNormalized['remote_schemas'][1]['definition']['customization']['type_names']['mapping']
        );
    }
}
