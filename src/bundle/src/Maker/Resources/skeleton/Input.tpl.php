<?= "<?php\n" ?>

declare(strict_types=1);

namespace <?= $namespace ?>;

use TheCodingMachine\GraphQLite\Annotations as GQL;

#[GQL\Input(name: '<?= $name ?>', default: true)]
final class Input
{
    #[GQL\Field(name: 'sample')]
    public string $sample;
}