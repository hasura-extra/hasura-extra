<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\SailorBridge\Tests\Type;

use Hasura\SailorBridge\Convert\JsonTypeConverter;
use Hasura\SailorBridge\Type\JsonTypeConfig;
use PHPUnit\Framework\TestCase;

final class JsonTypeConfigTest extends TestCase
{
    public function testTypeReference(): void
    {
        $config = new JsonTypeConfig('');

        $this->assertSame('array|object', $config->typeReference());
    }

    public function testTypeConverter(): void
    {
        $config = new JsonTypeConfig();

        $this->assertSame(JsonTypeConverter::class, $config->typeConverter());
    }

    public function testGenerateClasses(): void
    {
        $config = new JsonTypeConfig('');

        $this->assertSame([], $config->generateClasses());
    }
}
