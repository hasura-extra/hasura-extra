<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Fixture\App\GraphQL\Mutation;

use Hasura\Bundle\GraphQLite\Attribute\ArgEntity;
use Hasura\Bundle\GraphQLite\Attribute\ObjectAssertion;
use Hasura\Bundle\Tests\Fixture\App\Entity\Account;
use Hasura\GraphQLiteBridge\Attribute\ArgNaming;
use TheCodingMachine\GraphQLite\Annotations\Mutation;

final class ObjectAssertionTest
{
    #[Mutation(name: 'object_assertion_before_test', outputType: 'json!')]
    #[ArgNaming(for: 'objectInput', name: 'object_input')]
    #[ObjectAssertion(
        for: 'objectInput',
        mode: ObjectAssertion::BEFORE_RESOLVE_CALL,
        customViolationPropertyPaths: [
            'object_input.text_field' => 'text_field'
        ]
    )]
    public function before(
        InputObjectAssertion $objectInput
    ): array {
        return get_object_vars($objectInput);
    }

    #[Mutation(name: 'object_assertion_after_test')]
    #[ArgEntity(for: 'account')]
    #[ArgNaming(for: 'newEmail', name: 'new_email')]
    #[ObjectAssertion(for: 'account', mode: ObjectAssertion::AFTER_RESOLVE_CALL)]
    public function after(
        Account $account,
        ?string $newEmail = null
    ): string {
        if (null !== $newEmail) {
            $account->setEmail($newEmail);
        }

        return $account->getEmail();
    }
}