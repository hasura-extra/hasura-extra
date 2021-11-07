<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge;

use Hasura\ApiClient\Client;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

final class RemoteSchemaReloadStateProcessor implements StateProcessorInterface
{
    public function __construct(private RemoteSchemaInterface $remoteSchema, private Client $client)
    {
    }

    public function process(): void
    {
        try {
            $this->client->metadata()->query(
                'reload_remote_schema',
                ['name' => $this->remoteSchema->getName()]
            );
        } catch (ClientExceptionInterface $exception) {
            $response = $exception->getResponse();
            $data = $response->toArray(false);

            if (400 === $response->getStatusCode() && str_contains($data['error'], 'does not exist')) {
                throw new NotExistRemoteSchemaException($this->remoteSchema->getName(), $data['error']);
            }
        }
    }
}