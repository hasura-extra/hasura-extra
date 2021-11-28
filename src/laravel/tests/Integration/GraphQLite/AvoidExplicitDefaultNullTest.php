<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Integration\GraphQLite;

use GraphQL\Type\Schema;
use GraphQL\Utils\SchemaPrinter;
use Hasura\Laravel\Tests\TestCase;

final class AvoidExplicitDefaultNullTest extends TestCase
{
    public function testAvoid(): void
    {
        $sdl = SchemaPrinter::doPrint(
            $this->app[Schema::class]
        );

        $this->assertStringContainsString(
            'avoid_explicit_default_null_test(arg: String)',
            $sdl
        );
    }
}
