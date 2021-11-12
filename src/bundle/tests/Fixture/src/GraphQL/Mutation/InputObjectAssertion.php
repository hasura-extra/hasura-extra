<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Fixture\App\GraphQL\Mutation;

use Symfony\Component\Validator\Constraints as Assertion;
use TheCodingMachine\GraphQLite\Annotations\Field;
use TheCodingMachine\GraphQLite\Annotations\Input;

#[Input(name: 'input_object_assertion', default: true)]
final class InputObjectAssertion
{
    #[Field(name: 'email_field')]
    #[Assertion\Email]
    public string $emailField;

    #[Field(name: 'text_field')]
    #[Assertion\NotBlank]
    public string $textField;

    #[Field]
    #[Assertion\Valid]
    public SubInputObjectAssertion $sub;
}