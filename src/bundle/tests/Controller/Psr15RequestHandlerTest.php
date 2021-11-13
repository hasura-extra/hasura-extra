<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Controller;

use Hasura\Bundle\Controller\Psr15RequestHandler;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\Request;

final class Psr15RequestHandlerTest extends TestCase
{
    public function testHandleRequest(): void
    {
        $request = Request::create('/');
        $psr17Factory = new Psr17Factory();
        $psrFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
        $httpFoundationFactory = new HttpFoundationFactory();
        $handle = $this->createMock(RequestHandlerInterface::class);
        $handle
            ->expects($this->once())
            ->method('handle')
            ->willReturn($psr17Factory->createResponse(204));

        $handler = new Psr15RequestHandler($handle, $psrFactory, $httpFoundationFactory);
        $response = $handler($request);

        $this->assertSame('', $response->getContent());
        $this->assertSame(204, $response->getStatusCode());
    }
}