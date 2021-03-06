<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(
        Option::PATHS,
        [
            __DIR__ . '/src',
            __DIR__ . '/ecs.php',
            __DIR__ . '/monorepo-builder.php',
        ]
    );

    $parameters->set(
        Option::SKIP,
        [
            __DIR__ . '/src/bundle/tests/Fixture',
            __DIR__ . '/src/bundle/src/Maker/Resources/skeleton',
            __DIR__ . '/src/bundle/src/Resources/config',
            __DIR__ . '/src/bundle/src/DependencyInjection/Configuration.php',
        ]
    );

    $containerConfigurator->import(SetList::STRICT);
    $containerConfigurator->import(SetList::ARRAY);
    $containerConfigurator->import(SetList::DOCBLOCK);
    $containerConfigurator->import(SetList::PSR_12);
};
