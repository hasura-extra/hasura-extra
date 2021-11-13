<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Integration\GraphQLite;

use GraphQL\Utils\SchemaPrinter;
use TheCodingMachine\GraphQLite\Schema;

final class AvoidExplicitDefaultNullTest extends TestCase
{
    public function testAvoid(): void
    {
        $sdl = SchemaPrinter::doPrint(
            $this->client->getContainer()->get(Schema::class)
        );

        $this->assertStringContainsString(
            'avoid_explicit_default_null_test(arg: String)',
            $sdl
        );
    }
}