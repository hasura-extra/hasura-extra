<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Integration\GraphQLite;

use Hasura\Bundle\Tests\Integration\WebTestCase;

abstract class TestCase extends WebTestCase
{
    protected function execute($query, array $variables = null, array $server = []): void
    {
        $data = [
            'query' => $query,
        ];

        if (null !== $variables) {
            $data['variables'] = $variables;
        }

        $this->client->request(
            'POST',
            '/graphql',
            server: array_merge(
                [
                    'CONTENT_TYPE' => 'application/json',
                ],
                $server
            ),
            content: json_encode($data)
        );
    }
}
