<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\ApiClient\Tests;

use Hasura\ApiClient\Client;
use PHPUnit\Framework\TestCase;

/**
 * @mixin TestCase
 */
trait ClientSetupTrait
{
    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client('http://localhost:8080', 'test');
    }
}
