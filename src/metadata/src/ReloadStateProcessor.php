<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata;

use Hasura\ApiClient\Client;

final class ReloadStateProcessor implements StateProcessorInterface
{
    public function __construct(private Client $client)
    {
    }

    public function process(): void
    {
        $this->client->metadata()->query(
            'reload_metadata',
            [
                'reload_remote_schemas' => true,
                'reload_sources' => true
            ]
        );
    }
}