<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\GraphQLite;

use Hasura\Laravel\GraphQLite\AuthorizationService;
use Hasura\Laravel\Tests\TestCase;
use Illuminate\Auth\Access\Gate;

final class AuthorizationServiceTest extends TestCase
{
    public function testIsAllowed(): void
    {
        foreach ([true, false] as $isAllow) {
            $guard = $this->createMock(Gate::class);
            $guard->expects($this->once())->method('check')->willReturn($isAllow);
            $service = new AuthorizationService($guard);

            $this->assertSame($isAllow, $service->isAllowed(''));
        }
    }
}
