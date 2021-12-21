<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\SailorBridge\Tests\Converter;

use Hasura\SailorBridge\Converter\TimestamptzTypeConverter;
use PHPUnit\Framework\TestCase;

final class TimestamptzTypeConverterTest extends TestCase
{
    public function testConvert(): void
    {
        $converter = new TimestamptzTypeConverter();
        $timestamptz = $converter->fromGraphQL('2021-12-21T08:17:55+0000');
        $this->assertInstanceOf(\DateTimeImmutable::class, $timestamptz);
        $this->assertSame('2021-12-21T08:17:55+0000', $converter->toGraphQL($timestamptz));
    }

    public function testConvertToGraphQLThrowException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $converter = new TimestamptzTypeConverter();
        $converter->toGraphQL('2021-12-21');
    }

    public function testConvertFromGraphQLThrowException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $converter = new TimestamptzTypeConverter();
        $converter->fromGraphQL(true);
    }
}