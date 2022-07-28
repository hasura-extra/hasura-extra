<?php

declare(strict_types=1);

namespace Hasura\Bundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as SymfonyWebTestCase;

abstract class WebTestCase extends SymfonyWebTestCase
{
    protected string $projectDir = __DIR__ . '/Fixture';

    public static function getKernelClass(): string
    {
        return TestKernel::class;
    }
}
