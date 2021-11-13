<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Integration\GraphQLite;


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
        $this->execute($query, ['json' => ['test']]);
        $this->assertResponseIsSuccessful();

        $data = $this->responseData()['data']['root_type_mapper_test'];

        $this->assertArrayHasKey('json', $data);
        $this->assertArrayHasKey('jsonb', $data);
        $this->assertArrayHasKey('date', $data);
        $this->assertArrayHasKey('timestamptz', $data);
        $this->assertArrayHasKey('timetz', $data);
        $this->assertArrayHasKey('uuid', $data);

        $this->assertSame(['a' => 'b', 'b' => ['test']], $data['jsonb']);
    }
}