<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\SailorBridge\Tests\Type;

use Hasura\SailorBridge\Convert\UuidTypeConverter;
use Hasura\SailorBridge\Type\UuidTypeConfig;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class UuidTypeConfigTest extends TestCase
{
    public function testTypeReference(): void
    {
        $config = new UuidTypeConfig('');

        $this->assertSame(Uuid::class, $config->typeReference());
    }

    public function testTypeConverter(): void
    {
        $config = new UuidTypeConfig();

        $this->assertSame(UuidTypeConverter::class, $config->typeConverter());
    }

    public function testGenerateClasses(): void
    {
        $config = new UuidTypeConfig('');

        $this->assertSame([], $config->generateClasses());
    }
}