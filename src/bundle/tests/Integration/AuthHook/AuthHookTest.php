<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Integration\AuthHook;

use Hasura\Bundle\Tests\Integration\WebTestCase;

final class AuthHookTest extends WebTestCase
{
    public function testAnonymousRole(): void
    {
        $this->client->request('GET', '/hasura_auth_hook');

        $this->assertResponseIsSuccessful();

        $data = $this->responseData();

        $this->assertArrayHasKey('x-hasura-role', $data);
        $this->assertArrayHasKey('x-hasura-test', $data);
        $this->assertSame('ROLE_ANONYMOUS', $data['x-hasura-role']);
        $this->assertSame('test', $data['x-hasura-test']);
    }

    public function testDefaultRole(): void
    {
        $this->client->request(
            'GET',
            '/hasura_auth_hook',
            server: [
                'HTTP_AUTHORIZATION' => 'Basic ' . base64_encode('test:test')
            ]
        );

        $this->assertResponseIsSuccessful();

        $data = $this->responseData();

        $this->assertArrayHasKey('x-hasura-role', $data);
        $this->assertArrayHasKey('x-hasura-test', $data);
        $this->assertSame('ROLE_USER', $data['x-hasura-role']);
        $this->assertSame('test', $data['x-hasura-test']);
    }

    public function testRequestedRole(): void
    {
        $this->client->request(
            'GET',
            '/hasura_auth_hook',
            server: [
                'HTTP_X_HASURA_ROLE' => 'ROLE_TESTER',
                'HTTP_AUTHORIZATION' => 'Basic ' . base64_encode('test:test')
            ]
        );

        $this->assertResponseIsSuccessful();

        $data = $this->responseData();

        $this->assertArrayHasKey('x-hasura-role', $data);
        $this->assertArrayHasKey('x-hasura-test', $data);
        $this->assertSame('ROLE_TESTER', $data['x-hasura-role']);
        $this->assertSame('test', $data['x-hasura-test']);
    }
}