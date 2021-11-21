<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Integration\Sailor;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @mixin KernelTestCase
 */
trait SailorPathTrait
{
    private ?string $schemaPath = null;

    private ?string $executorPath = null;

    private ?string $querySpecPath = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->schemaPath = self::getContainer()->getParameter('hasura.sailor.schema_path');
        $this->executorPath = self::getContainer()->getParameter('hasura.sailor.executor_path');
        $this->querySpecPath = self::getContainer()->getParameter('hasura.sailor.query_spec_path');

        (new Filesystem())->mkdir($this->executorPath);
        (new Filesystem())->mkdir($this->querySpecPath);
    }

    protected function tearDown(): void
    {
        (new Filesystem())->remove($this->schemaPath);
        (new Filesystem())->remove($this->executorPath);
        (new Filesystem())->remove($this->querySpecPath);

        $this->schemaPath = $this->executorPath = $this->querySpecPath = null;

        parent::tearDown();
    }
}
