<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\EventDispatcher\Tests;

use Hasura\EventDispatcher\TableEvent;
use Hasura\EventDispatcher\TableEventRequestHandler;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;

final class TableEventRequestHandlerTest extends TestCase
{
    /**
     * @dataProvider payloadDataProvider
     */
    public function testDispatchEvent(string $payload)
    {
        $psrServerRequest = $this->mockPsrServerRequest($payload);
        $psrEventDispatcher = $this->mockPsrEventDispatcher($payload);
        $requestHandler = new TableEventRequestHandler($psrEventDispatcher, new Psr17Factory());
        $response = $requestHandler->handle($psrServerRequest);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('', $response->getBody()->getContents());
    }

    private function mockPsrServerRequest(string $payload): ServerRequestInterface
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream
            ->expects($this->once())
            ->method('getContents')
            ->willReturn($payload);

        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        return $request;
    }

    private function mockPsrEventDispatcher(string $payload): EventDispatcherInterface
    {
        $psrEventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $psrEventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->willReturnCallback(
                function (object $event) use ($payload) {
                    $payload = json_decode($payload, true);

                    $this->assertInstanceOf(TableEvent::class, $event);
                    $this->assertSame($payload['id'], $event->getId());
                    $this->assertSame($payload['trigger']['name'], $event->getTriggerName());
                    $this->assertSame($payload['table'], $event->getTable());
                    $this->assertSame($payload['event'], $event->getEvent());
                    $this->assertSame($payload['delivery_info'], $event->getDeliveryInfo());
                    $this->assertEquals(
                        new \DateTimeImmutable($payload['created_at']),
                        $event->getCreatedAt()
                    );
                }
            );

        return $psrEventDispatcher;
    }

    public static function payloadDataProvider(): array
    {
        return [
            'valid insert payload' => [
                '{"id":"707f3473-be53-431b-9bfc-a7870ce1f08f","event":{"op":"INSERT","data":{"new":{"id":13,"title":"a","author_name":"test"},"old":null},"trace_context":{"span_id":"da703f089531d9d2","trace_id":"a2dd66462a61f4bd"},"session_variables":{"x-hasura-role":"admin"}},"table":{"name":"article","schema":"_flattenmany"},"trigger":{"name":"article_events"},"created_at":"2021-10-28T06:46:52.317155Z","delivery_info":{"max_retries":0,"current_retry":0}}',
            ],
            'valid update payload' => [
                '{"id":"b6b42f6d-4031-46d7-b93e-98e6f9ea4e6f","event":{"op":"UPDATE","data":{"new":{"id":6,"title":"test 2","author_name":"Amie Sturman"},"old":{"id":6,"title":"test 1","author_name":"Amie Sturman"}},"trace_context":{"span_id":"9a27f1cecb3ae999","trace_id":"6d27f5ab417c8a59"},"session_variables":{"x-hasura-role":"admin"}},"table":{"name":"article","schema":"_flattenmany"},"trigger":{"name":"article_events"},"created_at":"2021-10-28T06:50:58.141099Z","delivery_info":{"max_retries":0,"current_retry":0}}',
            ],
            'valid delete payload' => [
                '{"id":"701a2d84-bc9d-4858-a649-f132f0ffa42d","event":{"op":"DELETE","data":{"new":null,"old":{"id":13,"title":"a","author_name":"test"}},"trace_context":{"span_id":"5155c27062327c40","trace_id":"e39f4a0647759246"},"session_variables":{"x-hasura-role":"admin"}},"table":{"name":"article","schema":"_flattenmany"},"trigger":{"name":"article_events"},"created_at":"2021-10-28T06:51:23.164244Z","delivery_info":{"max_retries":0,"current_retry":0}}',
            ],
            'valid manual payload' => [
                '{"id":"d169163a-94c6-4787-90af-b760aadc451f","event":{"op":"MANUAL","data":{"new":{"id":6,"title":"test 2","author_name":"Amie Sturman"},"old":null},"trace_context":{"span_id":"554495843c44d539","trace_id":"24d451e4fbce491a"},"session_variables":{"x-hasura-role":"admin"}},"table":{"name":"article","schema":"_flattenmany"},"trigger":{"name":"article_events"},"created_at":"2021-10-28T06:52:33.738982Z","delivery_info":{"max_retries":0,"current_retry":0}}',
            ],
        ];
    }
}
