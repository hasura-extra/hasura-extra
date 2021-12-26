<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\SailorBridge\Tests\Type;

use Hasura\SailorBridge\Convert\DateTypeConverter;
use Hasura\SailorBridge\Convert\TimestamptzTypeConverter;
use Hasura\SailorBridge\Convert\TimetzTypeConverter;
use Hasura\SailorBridge\Type\DateTimeTypeConfig;
use PHPUnit\Framework\TestCase;

final class DateTimeTypeConfigTest extends TestCase
{
    public function testTypeReference(): void
    {
        $config = new DateTimeTypeConfig('');

        $this->assertSame(\DateTimeInterface::class, $config->typeReference());
    }

    /**
     * @dataProvider typeConvertersProvider
     */
    public function testTypeConverter(string $typeName, string $expectedClass): void
    {
        $config = new DateTimeTypeConfig($typeName);

        $this->assertSame($expectedClass, $config->typeConverter());
    }

    public function testGenerateClasses(): void
    {
        $config = new DateTimeTypeConfig('');

        $this->assertSame([], $config->generateClasses());
    }

    public function typeConvertersProvider(): array
    {
        return [
            ['date', DateTypeConverter::class],
            ['timetz', TimetzTypeConverter::class],
            ['timestamptz', TimestamptzTypeConverter::class],
        ];
    }
}
