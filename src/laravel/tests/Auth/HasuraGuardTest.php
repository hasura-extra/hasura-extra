<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Auth;

use Hasura\Laravel\Tests\TestCase;
use Illuminate\Auth\GenericUser;
use Illuminate\Http\Request;

final class HasuraGuardTest extends TestCase
{
    public function testValidCredentials(): void
    {
        $request = Request::create('/', server: [
            'HTTP_AUTHORIZATION' => 'Basic ' . base64_encode('hasura:test'),
        ]);

        $requestGuard = $this->app['auth']->guard('hasura');
        $requestGuard->setRequest($request);

        $this->assertTrue($requestGuard->validate([
            'request' => $request,
        ]));
        $this->assertInstanceOf(GenericUser::class, $requestGuard->user());
    }

    /**
     * @dataProvider badCredentials
     */
    public function testBadCredentials(array $credentials): void
    {
        $request = Request::create('/', server: $credentials);

        $requestGuard = $this->app['auth']->guard('hasura');

        $this->assertFalse($requestGuard->validate([
            'request' => $request,
        ]));
    }

    public function badCredentials(): array
    {
        return [
            [
                [],
            ],
            [
                [
                    'HTTP_AUTHORIZATION' => '',
                ],
            ],
            [
                [
                    'HTTP_AUTHORIZATION' => 'Basic ' . base64_encode(':test'),
                ],
            ],
            [
                [
                    'HTTP_AUTHORIZATION' => 'Basic ' . base64_encode('hasura:'),
                ],
            ],
            [
                [
                    'HTTP_AUTHORIZATION' => 'Basic ' . base64_encode('h:t'),
                ],
            ],
        ];
    }
}
