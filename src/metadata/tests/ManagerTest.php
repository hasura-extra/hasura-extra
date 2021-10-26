<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata\Tests;

use Hasura\Metadata\EmptyMetadataException;

final class ManagerTest extends TestCase
{
    public function testApplyEmptyMetadata()
    {
        $this->expectException(EmptyMetadataException::class);

        $this->manager->apply();
    }

    public function testApplyMetadata()
    {
        $this->manager->export(true);

        $oldData = $this->client->metadata()->query('export_metadata', [], 2);

        $this->manager->apply();

        $newData = $this->client->metadata()->query('export_metadata', [], 2);

        $this->assertSame($oldData['metadata'], $newData['metadata']);
        $this->assertSame($oldData['resource_version'] + 1, $newData['resource_version']);
    }

    public function testReloadMetadata()
    {
        $this->manager->reload();
    }

    public function testExportMetadata()
    {
        $this->putDummyFileToMetadataPath();
        $this->manager->export(false);

        $this->assertFileExists(self::METADATA_PATH . '/dummy');
        $this->assertGreaterThan(1, count($this->getFilesInDir(self::METADATA_PATH)));

        $this->manager->export(true);
        $this->assertFileDoesNotExist(self::METADATA_PATH . '/dummy');
    }

    public function testClearMetadata()
    {
        $data = $this->client->metadata()->query('export_metadata', [], 2);

        $this->assertNotEmpty($data['metadata']['sources'][0]['tables']);
        $this->manager->clear();

        $data = $this->client->metadata()->query('export_metadata', [], 2);

        $this->assertEmpty($data['metadata']['sources'][0]['tables']);
    }

    public function testGetInconsistent()
    {
        $info = $this->manager->getInconsistentMetadata();

        $this->assertTrue($info['is_consistent']);

        $this->putInconsistentTable();

        $info = $this->manager->getInconsistentMetadata();

        $this->assertFalse($info['is_consistent']);
    }

    /**
     * @depends testGetInconsistent
     */
    public function testDropInconsistent()
    {
        $this->putInconsistentTable();

        $info = $this->manager->getInconsistentMetadata();

        $this->assertFalse($info['is_consistent']);
        $this->manager->dropInconsistentMetadata();

        $info = $this->manager->getInconsistentMetadata();

        $this->assertTrue($info['is_consistent']);
    }
}
