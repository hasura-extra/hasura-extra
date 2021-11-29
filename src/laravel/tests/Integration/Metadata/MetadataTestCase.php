<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Integration\Metadata;

use Hasura\Laravel\Tests\TestCase;
use Hasura\Metadata\ManagerInterface;
use Symfony\Component\Filesystem\Filesystem;

class MetadataTestCase extends TestCase
{
    private array $metadataBackup;

    protected function setUp(): void
    {
        parent::setUp();

        $this->metadataBackup = $this->app[ManagerInterface::class]->exportToArray();
    }

    protected function tearDown(): void
    {
        $this->app[ManagerInterface::class]->applyFromArray($this->metadataBackup);
        (new Filesystem())->remove(config('hasura.metadata.path'));

        parent::tearDown();
    }

    protected function putInconsistentTable(): void
    {
        $metadata = $this->app[ManagerInterface::class]->exportToArray();
        $metadata['sources'][0]['tables'][] = [
            'table' => [
                'schema' => 'public',
                'name' => 'inconsistent_table',
            ],
        ];

        $this->app[ManagerInterface::class]->applyFromArray($metadata, true);
    }
}