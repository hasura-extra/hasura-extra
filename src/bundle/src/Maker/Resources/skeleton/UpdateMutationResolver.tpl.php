<?= "<?php\n" ?>

declare(strict_types=1);

namespace <?= $namespace ?>;

use <?= $entity_full_class_name ?>;
use Hasura\Bundle\GraphQLite\Attribute\ArgEntity;
use Hasura\Bundle\GraphQLite\Attribute\ObjectAssertion;
use Hasura\Bundle\GraphQLite\Attribute\Transactional;
use Hasura\GraphQLiteBridge\Attribute\ArgNaming;
use TheCodingMachine\GraphQLite\Annotations as GQL;

final class Resolver
{
    #[GQL\Mutation(name: '<?= $name ?>', outputType: '<?= $output_type; ?>!')]
    #[ArgEntity(for: 'instance', inputType: 'ID!')]
    #[ArgNaming(for: 'inputObj', name: 'input_obj')]
    #[ObjectAssertion(for: 'inputObj')]
    #[Transactional]
    public function __invoke(<?= $entity_class_name ?> $instance, Input $inputObj): <?= $entity_class_name ?>

    {

    }
}