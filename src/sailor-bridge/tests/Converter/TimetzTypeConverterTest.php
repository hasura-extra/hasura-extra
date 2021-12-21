<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\SailorBridge\Tests\Converter;

use Hasura\SailorBridge\Converter\TimetzTypeConverter;
use PHPUnit\Framework\TestCase;

final class TimetzTypeConverterTest extends TestCase
{
    public function testConvert(): void
    {
        $converter = new TimetzTypeConverter();
        $timetz = $converter->fromGraphQL('08:17:55+0000');
        $this->assertInstanceOf(\DateTimeImmutable::class, $timetz);
        $this->assertSame('08:17:55+0000', $converter->toGraphQL($timetz));
    }

    public function testConvertToGraphQLThrowException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $converter = new TimetzTypeConverter();
        $converter->toGraphQL('08:17:55');
    }

    public function testConvertFromGraphQLThrowException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $converter = new TimetzTypeConverter();
        $converter->fromGraphQL(true);
    }
}