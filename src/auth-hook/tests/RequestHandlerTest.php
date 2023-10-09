<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\AuthHook\Tests;

use Hasura\AuthHook\AccessRoleDeciderInterface;
use Hasura\AuthHook\RequestHandler;
use Hasura\AuthHook\SessionVariableEnhancerInterface;
use Hasura\AuthHook\UnauthorizedException;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

final class RequestHandlerTest extends TestCase
{
    /**
     * @dataProvider mockDataProvider
     */
    public function testHandleRequest(
        string $header,
        string $headerValue,
        ?array $sessionVariables,
        bool $isUnauthorized,
        int $expectedHttpStatusCode,
        string $expectedContent
    ): void {
        $psr17Factory = new Psr17Factory();
        $accessRoleDecider = $this->mockAccessRoleDecider('x-hasura-role', $isUnauthorized);

        if (null !== $sessionVariables) {
            $sessionVariableEnhancer = $this->mockSessionVariableEnhancer($sessionVariables);
        } else {
            $sessionVariableEnhancer = null;
        }

        $request = $psr17Factory->createServerRequest('POST', '/')->withHeader($header, $headerValue);
        $requestHandler = new RequestHandler(
            $accessRoleDecider,
            $psr17Factory,
            $psr17Factory,
            $sessionVariableEnhancer,
        );
        $response = $requestHandler->handle($request);

        $response->getBody()->rewind();

        $this->assertSame($expectedHttpStatusCode, $response->getStatusCode());
        $this->assertSame('application/json', $response->getHeader('content-type')[0]);
        $this->assertSame($expectedContent, $response->getBody()->getContents());
    }


    private function mockAccessRoleDecider(string $headerName, bool $isUnauthorized): AccessRoleDeciderInterface
    {
        $mock = $this->createMock(AccessRoleDeciderInterface::class);
        $mock
            ->method('decideAccessRole')
            ->willReturnCallback(
                static function (ServerRequestInterface $serverRequest) use ($headerName, $isUnauthorized) {
                    if (false === $isUnauthorized) {
                        return $serverRequest->getHeader($headerName)[0] ?? 'anonymous';
                    }

                    throw new UnauthorizedException('unauthorized');
                }
            );

        return $mock;
    }

    private function mockSessionVariableEnhancer(array $variables): SessionVariableEnhancerInterface
    {
        $mock = $this->createMock(SessionVariableEnhancerInterface::class);
        $mock
            ->method('enhance')
            ->willReturn($variables);

        return $mock;
    }

    public static function mockDataProvider(): array
    {
        return [
            [
                'header' => 'x-hasura-role',
                'headerValue' => 'admin',
                'sessionVariables' => null,
                'unauthorized' => false,
                'expectedHttpStatusCode' => 200,
                'expectedContent' => json_encode(
                    [
                        'x-hasura-role' => 'admin',
                    ]
                ),
            ],
            [
                'header' => 'x-hasura-role',
                'headerValue' => 'admin',
                'sessionVariables' => [],
                'unauthorized' => false,
                'expectedHttpStatusCode' => 200,
                'expectedContent' => json_encode(
                    [
                        'x-hasura-role' => 'admin',
                    ]
                ),
            ],
            [
                'header' => 'x-hasura-role-2',
                'headerValue' => 'admin',
                'sessionVariables' => [
                    'x-hasura-user-id' => 1,
                ],
                'unauthorized' => false,
                'expectedHttpStatusCode' => 200,
                'expectedContent' => json_encode([
                    'x-hasura-user-id' => 1,
                    'x-hasura-role' => 'anonymous',
                    
                ]),
            ],
            [
                'header' => 'x-hasura-role',
                'headerValue' => 'admin',
                'sessionVariables' => [
                    'x-hasura-user-id' => 1,
                ],
                'unauthorized' => true,
                'expectedHttpStatusCode' => 401,
                'expectedContent' => json_encode([
                    'message' => 'unauthorized',
                    
                ]),
            ],
        ];
    }
}
