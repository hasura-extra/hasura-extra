<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\SailorBridge\Tests\Convert;

use Hasura\SailorBridge\Convert\TimestampTypeConverter;
use PHPUnit\Framework\TestCase;

final class TimestampTypeConverterTest extends TestCase
{
    public function testConvert(): void
    {
        $converter = new TimestampTypeConverter();
        $timestamptz = $converter->fromGraphQL('2021-12-21T08:17:55');
        $this->assertInstanceOf(\DateTimeImmutable::class, $timestamptz);
        $this->assertSame('2021-12-21T08:17:55', $converter->toGraphQL($timestamptz));
    }

    public function testConvertToGraphQLThrowException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $converter = new TimestampTypeConverter();
        $converter->toGraphQL('2021-12-21T08:17:55+0000');
    }

    public function testConvertFromGraphQLThrowException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $converter = new TimestampTypeConverter();
        $converter->fromGraphQL(false);
    }
}
