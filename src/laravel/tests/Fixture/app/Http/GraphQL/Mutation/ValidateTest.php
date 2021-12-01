<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Fixture\App\Http\GraphQL\Mutation;

use Hasura\GraphQLiteBridge\Attribute\ArgNaming;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
use TheCodingMachine\GraphQLite\Laravel\Annotations\Validate;

final class ValidateTest
{
    #[ArgNaming(for: 'email', name: 'email_naming')]
    #[Mutation(name: 'validate_test')]
    public function __invoke(
        #[Validate(['rule' => 'email'])]
        string $email
    ): string {
        return 'assertion: ' . $email;
    }
}