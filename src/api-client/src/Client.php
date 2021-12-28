<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\ApiClient;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class Client
{
    private HttpClientInterface $httpClient;

    public function __construct(
        string $baseUri,
        string $adminSecret = null,
        array $httpClientOptions = [],
        HttpClientInterface $httpClient = null
    ) {
        $httpClientOptions['base_uri'] = $baseUri;

        if ($adminSecret) {
            $httpClientOptions['headers']['X-Hasura-Admin-Secret'] = $adminSecret;
        }

        if (null === $httpClient) {
            $this->httpClient = HttpClient::create($httpClientOptions);
        } else {
            $this->httpClient = $httpClient->withOptions($httpClientOptions);
        }
    }

    public function metadata(): MetadataApi
    {
        return new MetadataApi($this->httpClient);
    }

    public function graphql(): GraphqlApi
    {
        return new GraphqlApi($this->httpClient);
    }

    public function relayGraphql(): RelayGraphqlApi
    {
        return new RelayGraphqlApi($this->httpClient);
    }

    public function version(): VersionApi
    {
        return new VersionApi($this->httpClient);
    }

    public function config(): ConfigApi
    {
        return new ConfigApi($this->httpClient);
    }
}
