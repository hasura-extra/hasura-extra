<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\ApiClient;

final class GraphqlApi extends AbstractApi
{
    public function query(string $query, array $variables = null, bool $throwOnError = false): array
    {
        $payload = ['query' => $query];

        if (null !== $variables) {
            $payload['variables'] = $variables;
        }

        $data = $this->request('POST', ['json' => $payload])->toArray();

        if (isset($data['errors']) && $throwOnError) {
            throw new GraphqlApiException($data['errors'], 'Graphql response data errors!');
        }

        return $data;
    }

    protected function apiPath(): string
    {
        return '/v1/graphql';
    }
}
