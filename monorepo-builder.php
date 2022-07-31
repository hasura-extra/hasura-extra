<?php

declare(strict_types=1);

use Hasura\MonorepoBuilder\Git\TagResolver;
use Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\Contract\Git\TagResolverInterface;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushNextDevReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\PushTagReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetCurrentMutualConflictsReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetCurrentMutualDependenciesReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\SetNextMutualDependenciesReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\TagVersionReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\UpdateBranchAliasReleaseWorker;
use Symplify\MonorepoBuilder\Release\ReleaseWorker\UpdateReplaceReleaseWorker;

return static function (MBConfig $mbConfig): void {
    $mbConfig->defaultBranch('main');

    $mbConfig->workers([
        // release workers - in order to execute
        UpdateReplaceReleaseWorker::class,
        SetCurrentMutualConflictsReleaseWorker::class,
        SetCurrentMutualDependenciesReleaseWorker::class,
        TagVersionReleaseWorker::class,
        PushTagReleaseWorker::class,
        SetNextMutualDependenciesReleaseWorker::class,
        UpdateBranchAliasReleaseWorker::class,
        PushNextDevReleaseWorker::class,
    ]);

    $mbConfig->packageDirectories([__DIR__ . '/src']);
    $mbConfig->packageDirectoriesExcludes([__DIR__ . '/src/monorepo-builder']);

    $mbConfig->dataToAppend(
        [
            ComposerJsonSection::REQUIRE_DEV => [
                'phpunit/phpunit' => '^9.5',
            ],
        ]
    );
    $mbConfig->dataToRemove([
        'minimum-stability' => 'dev',
        'prefer-stable' => true,
    ]);

    $mbConfig->services()->set(TagResolver::class)->autowire();
    $mbConfig->services()->alias(TagResolverInterface::class, TagResolver::class);
};
