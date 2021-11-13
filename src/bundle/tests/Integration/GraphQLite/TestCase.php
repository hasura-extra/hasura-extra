<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Integration\GraphQLite;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class TestCase extends WebTestCase
{
    protected ?KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->client = null;
    }

    protected function execute($query, array $variables = null): void
    {
        $data = [
            'query' => $query
        ];

        if (null !== $variables) {
            $data['variables'] = $variables;
        }

        $this->client->request(
            'POST',
            '/graphql',
            server: [
                'CONTENT_TYPE' => 'application/json'
            ],
            content: json_encode($data)
        );
    }

    protected function responseData(): array
    {
        return json_decode($this->client->getResponse()->getContent(), true);
    }
}