<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\ApiClient;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class AbstractApi
{
    public function __construct(private HttpClientInterface $httpClient)
    {
    }

    protected function request(string $method, array $options = []): ResponseInterface
    {
        return $this->httpClient->request($method, $this->apiPath(), $options);
    }

    abstract protected function apiPath(): string;
}
