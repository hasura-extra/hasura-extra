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
use Hasura\SailorBridge\Converter\UuidTypeConverter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class UuidTypeConverterTest extends TestCase
{
    public function testConvert(): void
    {
        $converter = new UuidTypeConverter();
        $uuid = $converter->fromGraphQL('c4ec88c8-3734-4698-8fcf-afef84883eb7');
        $this->assertInstanceOf(Uuid::class, $uuid);
        $this->assertSame('c4ec88c8-3734-4698-8fcf-afef84883eb7', $converter->toGraphQL($uuid));
    }

    public function testConvertToGraphQLThrowException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $converter = new UuidTypeConverter();
        $converter->toGraphQL('c4ec88c8-3734-4698-8fcf-afef84883eb');
    }

    public function testConvertFromGraphQLThrowException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $converter = new UuidTypeConverter();
        $converter->fromGraphQL('c4ec88c8-3734-4698-8fcf-afef84883eb777');
    }
}