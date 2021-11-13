<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Integration;

use Hasura\Bundle\Tests\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;

abstract class ConsoleTestCase extends KernelTestCase
{
    protected ?Application $application;

    protected function setUp(): void
    {
        parent::setUp();

        $this->application = new Application(self::bootKernel());
    }

    protected function tearDown(): void
    {
        $this->application = null;

        parent::tearDown();
    }

    protected function getCommand(string $name): Command
    {
        return $this->application->get($name);
    }
}