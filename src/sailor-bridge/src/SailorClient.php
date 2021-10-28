<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\SailorBridge;

use Hasura\ApiClient\Client;
use Spawnia\Sailor\Client as SailorClientInterface;
use Spawnia\Sailor\Response;

final class SailorClient implements SailorClientInterface
{
    public function __construct(private Client $client)
    {
    }

    public function request(string $query, \stdClass $variables = null): Response
    {
        $data = $this->client->graphql()->query($query, $variables ? get_object_vars($variables) : null);

        return Response::fromJson(json_encode($data));
    }
}