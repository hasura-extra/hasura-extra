<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Fixture\App\GraphQL\Mutation;

use Hasura\GraphQLiteBridge\Attribute\ArgNaming;
use Symfony\Component\Validator\Constraints as Assert;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
use TheCodingMachine\GraphQLite\Validator\Annotations\Assertion;

final class AssertionTest
{
    /**
     * @Assertion(for="email", constraint={@Assert\Email()})
     */
    #[ArgNaming(for: 'email', name: 'email_naming')]
    #[Mutation(name: 'assertion_test')]
    public function __invoke(
        string $email
    ): string {
        return 'assertion: ' . $email;
    }
}