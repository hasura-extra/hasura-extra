<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Tests\Attribute;

use Hasura\GraphQLiteBridge\Attribute\Roles;
use Hasura\GraphQLiteBridge\Tests\TestCase;

final class RolesTest extends TestCase
{
    public function testConstructorWithEmptyRoleNames(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Role names is required!');

        new Roles();
    }

    public function testConstructor(): void
    {
        $roles = new Roles('a', 'b');

        $this->assertSame(['a', 'b'], $roles->getNames());
    }
}
