<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Integration\EventDispatcher;

use Hasura\Bundle\Tests\Fixture\App\EventSubscriber\TableEventSubscriber;
use Hasura\EventDispatcher\TableEvent;
use Hasura\Laravel\Tests\TestCase;

final class TableEventTest extends TestCase
{
    public function testTrigger(): void
    {
        $triggeredId = null;

        $this->app['events']->listen(function (TableEvent $event) use (&$triggeredId) {
            $triggeredId = $event->getId();
        });

        $jsonPayload = '{"id":"707f3473-be53-431b-9bfc-a7870ce1f08f","event":{"op":"INSERT","data":{"new":{"id":13,"title":"a","author_name":"test"},"old":null},"trace_context":{"span_id":"da703f089531d9d2","trace_id":"a2dd66462a61f4bd"},"session_variables":{"x-hasura-role":"admin"}},"table":{"name":"article","schema":"_flattenmany"},"trigger":{"name":"article_events"},"created_at":"2021-10-28T06:46:52.317155Z","delivery_info":{"max_retries":0,"current_retry":0}}';

        $response = $this->call(
                     'POST',
                     '/hasura-table-event',
            server:  ['HTTP_AUTHORIZATION' => 'Basic ' . base64_encode('hasura:test')],
            content: $jsonPayload
        );

        $response->assertSuccessful();

        $this->assertNotNull($triggeredId);
        $this->assertSame('707f3473-be53-431b-9bfc-a7870ce1f08f', $triggeredId);
    }

    public function testUnsafeRequest(): void
    {
        $jsonPayload = '{"id":"707f3473-be53-431b-9bfc-a7870ce1f08f","event":{"op":"INSERT","data":{"new":{"id":13,"title":"a","author_name":"test"},"old":null},"trace_context":{"span_id":"da703f089531d9d2","trace_id":"a2dd66462a61f4bd"},"session_variables":{"x-hasura-role":"admin"}},"table":{"name":"article","schema":"_flattenmany"},"trigger":{"name":"article_events"},"created_at":"2021-10-28T06:46:52.317155Z","delivery_info":{"max_retries":0,"current_retry":0}}';

        $response = $this->call(
                     'POST',
                     '/hasura-table-event',
            server:  ['HTTP_ACCEPT' => 'application/json'],
            content: $jsonPayload
        );

        $response->assertUnauthorized();
    }
}
