<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\SailorBridge\Tests\Convert;

use Hasura\SailorBridge\Convert\JsonTypeConverter;
use PHPUnit\Framework\TestCase;

final class JsonTypeConverterTest extends TestCase
{
    public function testConvert(): void
    {
        $converter = new JsonTypeConverter();
        $data = ['a' => 'b', 'c' => 'd'];

        $this->assertSame($data, $converter->toGraphQL($data));
        $this->assertSame($data, $converter->fromGraphQL($data));
    }

    public function testConvertToGraphQLThrowException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $converter = new JsonTypeConverter();
        $converter->toGraphQL('');
    }

    public function testConvertFromGraphQLThrowException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $converter = new JsonTypeConverter();
        $converter->fromGraphQL(1);
    }
}