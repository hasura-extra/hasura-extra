<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata\Tests;

use Hasura\ApiClient\Client;
use Hasura\Metadata\Manager;
use Hasura\Metadata\ManagerInterface;
use Hasura\Metadata\MetadataUtils;
use Hasura\Metadata\YamlOperator;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Symfony\Component\Filesystem\Filesystem;

class TestCase extends PHPUnitTestCase
{
    protected const METADATA_PATH = __DIR__ . '/.metadata';

    protected ManagerInterface $manager;

    protected Client $client;

    private array $backupMetadata;

    protected function setUp(): void
    {
        parent::setUp();

        $client = $this->client = new Client('http://localhost:8080', 'test');
        $this->manager = new Manager(
            $client,
            self::METADATA_PATH,
            new YamlOperator(new Filesystem())
        );

        $this->backupMetadata = $this->getCurrentMetadata();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $filesystem = new Filesystem();

        $filesystem->remove(self::METADATA_PATH);
        $filesystem->mkdir(self::METADATA_PATH);

        $this->client->metadata()->query(
            'replace_metadata',
            [
                'metadata' => $this->backupMetadata,
                'allow_inconsistent_metadata' => false,
            ],
            2
        );
    }

    protected function getFilesInDir(string $dir): array
    {
        $files = iterator_to_array(
            new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::LEAVES_ONLY
            )
        );

        return array_map(
            fn (string $file) => rtrim((new Filesystem())->makePathRelative($file, $dir), '/'),
            array_keys($files)
        );
    }

    protected function putInconsistentTable(): void
    {
        $data = $this->client->metadata()->query('export_metadata', [], 2);
        $metadata = MetadataUtils::normalizeMetadata($data['metadata']);
        $metadata['sources'][0]['tables'][] = [
            'table' => [
                'schema' => 'public',
                'name' => 'inconsistent_table',
            ],
        ];

        $this->client->metadata()->query(
            'replace_metadata',
            [
                'metadata' => $metadata,
                'allow_inconsistent_metadata' => true,
            ],
            2
        );
    }

    protected function putDummyFileToMetadataPath(): void
    {
        file_put_contents(self::METADATA_PATH . '/dummy', '');
    }

    protected function getCurrentMetadata(bool $normalize = true): array
    {
        $data = $this->client->metadata()->query('export_metadata', [], 2);

        return $normalize ? MetadataUtils::normalizeMetadata($data['metadata']) : $data['metadata'];
    }
}
