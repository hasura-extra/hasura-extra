<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Integration\EventDispatcher;

use Hasura\Bundle\Tests\Fixture\App\EventSubscriber\TableEventSubscriber;
use Hasura\Bundle\Tests\Integration\WebTestCase;

final class TableEventTest extends WebTestCase
{
    public function testTrigger(): void
    {
        $jsonPayload = '{"id":"707f3473-be53-431b-9bfc-a7870ce1f08f","event":{"op":"INSERT","data":{"new":{"id":13,"title":"a","author_name":"test"},"old":null},"trace_context":{"span_id":"da703f089531d9d2","trace_id":"a2dd66462a61f4bd"},"session_variables":{"x-hasura-role":"admin"}},"table":{"name":"article","schema":"_flattenmany"},"trigger":{"name":"article_events"},"created_at":"2021-10-28T06:46:52.317155Z","delivery_info":{"max_retries":0,"current_retry":0}}';

        $this->client->request(
            'POST',
            '/hasura_table_event',
            content: $jsonPayload
        );

        $this->assertResponseIsSuccessful();

        /** @var TableEventSubscriber $subscriber */
        $subscriber = self::getContainer()->get(TableEventSubscriber::class);

        $this->assertNotNull($subscriber->lastEvent);
        $this->assertSame('707f3473-be53-431b-9bfc-a7870ce1f08f', $subscriber->lastEvent->getId());
    }
}