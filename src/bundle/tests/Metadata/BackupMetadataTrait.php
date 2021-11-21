<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Metadata;

use Hasura\ApiClient\Client;
use Hasura\Bundle\Tests\KernelTestCase;
use Hasura\Metadata\MetadataUtils;

/**
 * @mixin KernelTestCase
 */
trait BackupMetadataTrait
{
    protected ?Client $client;

    private ?array $metadataBackup;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::bootKernel()->getContainer()->get('hasura.api_client.client');

        $data = $this->client->metadata()->query('export_metadata', [], 2);
        $this->metadataBackup = MetadataUtils::normalizeMetadata($data['metadata']);
    }

    protected function tearDown(): void
    {
        $this->client->metadata()->query(
            'replace_metadata',
            [
                'allow_inconsistent_metadata' => false,
                'metadata' => $this->metadataBackup,
            ]
        );

        $this->client = $this->metadataBackup = null;

        parent::tearDown();
    }
}
