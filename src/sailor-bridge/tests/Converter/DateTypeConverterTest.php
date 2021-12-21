<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\SailorBridge\Tests\Converter;

use Hasura\SailorBridge\Converter\DateTypeConverter;
use PHPUnit\Framework\TestCase;

final class DateTypeConverterTest extends TestCase
{
    public function testConvert(): void
    {
        $converter = new DateTypeConverter();
        $date = $converter->fromGraphQL('2021-12-21');
        $this->assertInstanceOf(\DateTimeImmutable::class, $date);
        $this->assertSame('2021-12-21', $converter->toGraphQL($date));
    }

    public function testConvertToGraphQLThrowException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $converter = new DateTypeConverter();
        $converter->toGraphQL('2021-12-21 12:12:12');
    }

    public function testConvertFromGraphQLThrowException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $converter = new DateTypeConverter();
        $converter->fromGraphQL(false);
    }
}