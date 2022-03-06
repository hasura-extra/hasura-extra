<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Integration;

use Hasura\Bundle\Tests\WebTestCase as AbstractWebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

abstract class WebTestCase extends AbstractWebTestCase
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

    protected function responseData(): array
    {
        return json_decode($this->client->getResponse()->getContent(), true);
    }
}
