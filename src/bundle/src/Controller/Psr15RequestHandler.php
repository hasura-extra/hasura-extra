<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Controller;

use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Bridge\PsrHttpMessage\HttpFoundationFactoryInterface;
use Symfony\Bridge\PsrHttpMessage\HttpMessageFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class Psr15RequestHandler
{
    public function __construct(
        private RequestHandlerInterface $requestHandler,
        private HttpMessageFactoryInterface $psrFactory,
        private HttpFoundationFactoryInterface $foundationFactory
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $serverRequest = $this->psrFactory->createRequest($request);

        $response = $this->requestHandler->handle($serverRequest);

        return $this->foundationFactory->createResponse($response);
    }
}