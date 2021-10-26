<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata\Tests;

use Hasura\Metadata\YamlOperator;
use Symfony\Component\Filesystem\Filesystem;

final class YamlOperatorTest extends TestCase
{
    private YamlOperator $operator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->operator = new YamlOperator(new Filesystem());
    }

    public function testExport()
    {
        $metadata = $this->getCurrentMetadata();

        $this->assertEmpty($this->getFilesInDir(self::METADATA_PATH));
        $this->putDummyFileToMetadataPath();

        $this->operator->export($metadata, self::METADATA_PATH, false);

        $this->assertNotEmpty($this->getFilesInDir(self::METADATA_PATH));
        $this->assertFileExists(self::METADATA_PATH . '/dummy');

        $this->operator->export($metadata, self::METADATA_PATH, true);

        $this->assertFileDoesNotExist(self::METADATA_PATH . '/dummy');
    }

    /**
     * @depends testExport
     */
    public function testLoad()
    {
        $metadata = $this->operator->load(self::METADATA_PATH);

        $this->assertEmpty($metadata);

        $this->operator->export($this->getCurrentMetadata(), self::METADATA_PATH, true);

        $metadataLoaded = $this->operator->load(self::METADATA_PATH);

        $this->assertNotEmpty($metadataLoaded);

        $this->client->metadata()->query(
            'replace_metadata',
            ['metadata' => $metadataLoaded, 'allow_inconsistent_metadata' => false],
            2
        );
    }
}