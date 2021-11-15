<?= "<?php\n" ?>
declare(strict_types=1);

namespace <?= $namespace ?>;

use <?= $entity_full_class_name ?>;
use TheCodingMachine\GraphQLite\Annotations as GQL;

#[GQL\Type(class: <?= $entity_class_name; ?>::class, name: '<?= $name ?>', default: false)]
#[GQL\SourceField(name: 'id', outputType: 'ID!')]
final class Output
{
}