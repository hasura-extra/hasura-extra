<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Integration\GraphQLite;

use Hasura\Laravel\Tests\TestCase;

final class RootTypeMapperTest extends TestCase
{
    public function testRootTypeMapper(): void
    {
        $query = /** @lang GraphQL */
            <<<'GQL'
query Query($json: json!) {
    root_type_mapper_test(
        json: $json,
        jsonb: {a: "b", b: $json},
        date: "2021-11-13",
        timestamptz: "2021-11-13T01:01:01+00",
        timetz: "01:01:01+00",
        uuid: "39b9a372-8b75-4fe5-8cc3-eb15e64f7647"
    )
}
GQL;
        $response = $this->graphql($query, [
            'json' => ['test'],
        ]);

        $response->assertSuccessful();
        $response->assertJson(
            [
                'data' => [
                    'root_type_mapper_test' => [
                        'json' => ['test'],
                    ],
                ],
            ],
            true
        );
    }
}
