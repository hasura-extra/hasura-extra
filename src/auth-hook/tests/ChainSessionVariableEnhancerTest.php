<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\AuthHook\Tests;

use Hasura\AuthHook\ChainSessionVariableEnhancer;
use Hasura\AuthHook\SessionVariableEnhancerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

final class ChainSessionVariableEnhancerTest extends TestCase
{
    public function testEnhanceSessionVariables(): void
    {
        $enhancer1 = $this->createMockEnhancer([
            'a' => 1,
            'c' => 3,
        ]);
        $enhancer2 = $this->createMockEnhancer([
            'b' => 2,
        ]);
        $enhancer3 = $this->createMockEnhancer([
            'c' => 'override',
        ]);

        $chain = new ChainSessionVariableEnhancer([$enhancer1, $enhancer2, $enhancer3]);
        $sessionVariables = $chain->enhance([
            'd' => 4,
        ], $this->createMock(ServerRequestInterface::class));

        $this->assertSame([
            'd' => 4,
            'a' => 1,
            'c' => 'override',
            'b' => 2,
        ], $sessionVariables);
    }

    private function createMockEnhancer(array $appendSessionVariables): SessionVariableEnhancerInterface
    {
        $enhancer = $this->createMock(SessionVariableEnhancerInterface::class);
        $enhancer
            ->expects($this->once())
            ->method('enhance')
            ->willReturnCallback(
                static fn (array $sessionVariables) => array_merge($sessionVariables, $appendSessionVariables)
            );

        return $enhancer;
    }
}
