<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\AuthHook;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class RequestHandler implements RequestHandlerInterface
{
    public function __construct(
        private AccessRoleDeciderInterface $roleDecider,
        private ResponseFactoryInterface $responseFactory,
        private StreamFactoryInterface $streamFactory,
        private ?SessionVariableEnhancerInterface $sessionVariableEnhancer = null,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $role = $this->roleDecider->decideAccessRole($request);
        } catch (UnauthorizedException $exception) {
            return $this->makeJsonResponse(['message' => $exception->getMessage()], 401);
        }

        $sessionVariables = ['x-hasura-role' => $role];

        if (null !== $this->sessionVariableEnhancer) {
            $sessionVariables = $this->sessionVariableEnhancer->enhance($sessionVariables, $request);
        }

        // Respect role decider
        $sessionVariables['x-hasura-role'] = $role;

        return $this->makeJsonResponse($sessionVariables);
    }

    private function makeJsonResponse(array $data, int $httpStatusCode = 200): ResponseInterface
    {
        $contentStream = $this->streamFactory->createStream(json_encode($data));

        return $this->responseFactory
            ->createResponse()
            ->withStatus($httpStatusCode)
            ->withHeader('content-type', 'application/json')
            ->withBody($contentStream);
    }
}