<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Fixture\App\Http\GraphQL\Mutation;

use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Input;

#[Input(name: 'input_object_assertion', default: true)]
final class InputObjectAssertion
{
    #[Field(name: 'email_field')]
    public string $emailField;

    #[Field(name: 'text_field')]
    public string $textField;

    #[Field]
    public SubInputObjectAssertion $sub;

    public function rules(): array
    {
        return [
            'emailField' => 'email',
            'textField' => 'required',
            'sub.subTextField' => 'required'
        ];
    }
}