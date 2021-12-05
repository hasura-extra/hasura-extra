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
use Hasura\Laravel\GraphQLite\Attribute\ValidateObject;
use TheCodingMachine\GraphQLite\Annotations\Mutation;

final class ValidateObjectTest
{
    #[Mutation(name: 'validate_object_test', outputType: 'json!')]
    #[ArgNaming(for: 'objectInput', name: 'object_input')]
    #[ValidateObject(
        for: 'objectInput',
        customErrorArgumentNames: [
            'object_input.text_field' => 'text_field',
        ]
    )]
    public function __invoke(
        ValidateObjectInput $objectInput
    ): array {
        return get_object_vars($objectInput);
    }
}
