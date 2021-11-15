<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase as SymfonyKernelTestCase;

abstract class KernelTestCase extends SymfonyKernelTestCase
{
    protected string $projectDir = __DIR__ . '/Fixture';

    public static function getKernelClass()
    {
        return TestKernel::class;
    }
}