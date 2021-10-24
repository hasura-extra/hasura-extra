<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\ApiClient\Tests;

use PHPUnit\Framework\TestCase;

final class ConfigApiTest extends TestCase
{
    use ClientSetupTrait;

    public function testGet(): void
    {
        $data = $this->client->config()->get();

        $this->assertIsArray($data);
        $this->assertArrayHasKey('version', $data);
        $this->assertSame('v2.1.0-beta.1', $data['version']);
    }
}