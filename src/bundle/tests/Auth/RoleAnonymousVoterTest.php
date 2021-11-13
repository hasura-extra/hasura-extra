<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Auth;

use Hasura\Bundle\Auth\RoleAnonymousVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\NullToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class RoleAnonymousVoterTest extends TestCase
{
    public function testVote(): void
    {
        $voter = new RoleAnonymousVoter('anonymous');

        $this->assertSame(
            Voter::ACCESS_GRANTED,
            $voter->vote(new NullToken(), null, ['anonymous'])
        );

        $this->assertSame(
            Voter::ACCESS_ABSTAIN,
            $voter->vote(new NullToken(), null, [])
        );

        $this->assertSame(
            Voter::ACCESS_DENIED,
            $voter->vote($this->createMock(TokenInterface::class), null, ['anonymous'])
        );
    }
}