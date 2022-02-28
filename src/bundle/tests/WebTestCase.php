<?php
/*
 * (c) Tai Vu <vcttai@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as SymfonyWebTestCase;

abstract class WebTestCase extends SymfonyWebTestCase
{
    protected string $projectDir = __DIR__ . '/Fixture';

    public static function getKernelClass()
    {
        return TestKernel::class;
    }
}