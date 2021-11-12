<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Fixture\App\GraphQL\Mutation;

use Hasura\Bundle\GraphQLite\Attribute\ObjectAssertion;
use Hasura\GraphQLiteBridge\Attribute\ArgNaming;
use TheCodingMachine\GraphQLite\Annotations\Mutation;

final class ObjectAssertionTest
{
    #[Mutation(name: 'object_assertion_test')]
    #[ArgNaming(for: 'objectInput', name: 'object_input')]
    #[ObjectAssertion(for: 'objectInput')]
    public function __invoke(InputObjectAssertion $objectInput): string
    {
        return 'test';
    }
}