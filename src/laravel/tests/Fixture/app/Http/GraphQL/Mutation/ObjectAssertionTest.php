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
use Hasura\Laravel\GraphQLite\Attribute\ObjectAssertion;
use TheCodingMachine\GraphQLite\Annotations\Mutation;

final class ObjectAssertionTest
{
    #[Mutation(name: 'object_assertion_test', outputType: 'json!')]
    #[ArgNaming(for: 'objectInput', name: 'object_input')]
    #[ObjectAssertion(
        for: 'objectInput',
        customErrorArgumentNames: [
            'object_input.text_field' => 'text_field'
        ]
    )]
    public function __invoke(
        InputObjectAssertion $objectInput
    ): array {
        return get_object_vars($objectInput);
    }
}