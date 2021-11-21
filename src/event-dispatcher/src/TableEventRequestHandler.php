<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\EventDispatcher;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class TableEventRequestHandler implements RequestHandlerInterface
{
    public function __construct(
        private EventDispatcherInterface $dispatcher,
        private ResponseFactoryInterface $responseFactory
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $jsonPayload = $request->getBody()->getContents();
        $payload = json_decode($jsonPayload, true);
        $event = new TableEvent(
            $payload['id'],
            $payload['trigger']['name'],
            $payload['table'],
            $payload['event'],
            $payload['delivery_info'],
            new \DateTimeImmutable($payload['created_at'])
        );

        $this->dispatcher->dispatch($event);

        return $this->responseFactory->createResponse();
    }
}
