<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Tests;

use Hasura\ApiClient\Client;
use Hasura\Metadata\MetadataUtils;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use TheCodingMachine\GraphQLite\Schema;

class TestCase extends PHPUnitTestCase
{
    use SchemaFactoryTrait;

    protected Schema $schema;

    protected Client $client;

    protected bool $autoBackupAndRestoreMetadata = false;

    private array $metadataBackup;

    protected function setUp(): void
    {
        parent::setUp();

        $this->initSchemaFactory();

        $this->schema = $this->schemaFactory->createSchema();
        $this->client = new Client('http://localhost:8080', 'test');

        if ($this->autoBackupAndRestoreMetadata) {
            $data = $this->client->metadata()->query('export_metadata', [], 2);
            $this->metadataBackup = MetadataUtils::normalizeMetadata($data['metadata']);
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        if ($this->autoBackupAndRestoreMetadata) {
            $this->client->metadata()->query(
                'replace_metadata',
                [
                    'metadata' => $this->metadataBackup,
                    'allow_inconsistent_metadata' => false
                ],
                2
            );
        }
    }
}