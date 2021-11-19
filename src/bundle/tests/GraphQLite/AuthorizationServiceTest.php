<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\GraphQLite;

use Hasura\Bundle\GraphQLite\AuthorizationService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class AuthorizationServiceTest extends TestCase
{
    public function testMissingSecurityBundle(): void
    {
        $this->expectException(\LogicException::class);

        $service = new AuthorizationService(null);
        $service->isAllowed('');
    }

    public function testIsAllowed(): void
    {
        foreach ([true, false] as $isAllow) {
            $checker = $this->createMock(AuthorizationCheckerInterface::class);
            $checker
                ->expects($this->once())
                ->method('isGranted')
                ->willReturn($isAllow);

            $service = new AuthorizationService($checker);
            $this->assertSame($isAllow, $service->isAllowed(''));
        }
    }
}