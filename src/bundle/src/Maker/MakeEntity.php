<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Maker;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Maker\MakeEntity as BaseMakeEntity;
use Symfony\Bundle\MakerBundle\MakerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use function Symfony\Component\String\u;

final class MakeEntity extends AbstractMaker
{
    public function __construct(private MakerInterface $maker, private Generator $generator)
    {
    }

    public static function getCommandName(): string
    {
        return BaseMakeEntity::getCommandName();
    }

    public function interact(InputInterface $input, ConsoleStyle $io, Command $command)
    {
        $this->maker->interact($input, $io, $command);
        $entityClassName = $input->getArgument('name');
        $insertNamespace = sprintf('GraphQL\\%s\\InsertMutation\\', $entityClassName);
        $updateNamespace = sprintf('GraphQL\\%s\\UpdateMutation\\', $entityClassName);
        $insertResolverClassDetails = $this->generator->createClassNameDetails('Resolver', $insertNamespace);
        $updateResolverClassDetails = $this->generator->createClassNameDetails('Resolver', $updateNamespace);

        if (
            !$input->getOption('graphql')
            && !class_exists($insertResolverClassDetails->getFullName())
            && !class_exists($updateResolverClassDetails->getFullName())
        ) {
            $description = $command->getDefinition()->getOption('graphql')->getDescription();
            $question = new ConfirmationQuestion($description, false);
            $isGraphql = $io->askQuestion($question);

            $input->setOption('graphql', $isGraphql);
        }
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig)
    {
        $command->addOption('graphql', 'g', InputOption::VALUE_NONE, 'Add GraphQL insert/update mutations');
        $this->maker->configureCommand($command, $inputConfig);
    }

    public function configureDependencies(DependencyBuilder $dependencies)
    {
        $this->maker->configureDependencies($dependencies);
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        if (!$input->getOption('graphql')) {
            $this->maker->generate($input, $io, $generator);

            return;
        }

        $entity = u($input->getArgument('name'))->title()->toString();
        $entityClassDetails = $generator->createClassNameDetails(
            $entity,
            'Entity\\'
        );

        foreach (['Update', 'Insert'] as $mutationName) {
            $name = u(sprintf('%s_%s', $entity, $mutationName))->snake()->lower()->toString();
            $namespace = sprintf('GraphQL\\%s\\%sMutation\\', $entity, $mutationName);
            $outputType = u(sprintf('%s_%s_mutation_output', $entity, $mutationName))
                ->snake()
                ->lower()
                ->toString()
            ;
            $resolverClassDetails = $generator->createClassNameDetails('Resolver', $namespace);
            $generator->generateClass(
                $resolverClassDetails->getFullName(),
                sprintf(__DIR__ . '/Resources/skeleton/%sMutationResolver.tpl.php', $mutationName),
                [
                    'entity_full_class_name' => $entityClassDetails->getFullName(),
                    'entity_class_name' => $entityClassDetails->getShortName(),
                    'output_type' => $outputType,
                    'name' => $name,
                ]
            );

            foreach (['Input', 'Output'] as $ioName) {
                $name = u(sprintf('%s_%s_mutation_%s', $entity, $mutationName, $ioName))
                    ->snake()
                    ->lower()
                    ->toString()
                ;
                $ioClassDetails = $generator->createClassNameDetails($ioName, $namespace);
                $generator->generateClass(
                    $ioClassDetails->getFullName(),
                    sprintf('%s/Resources/skeleton/%s.tpl.php', __DIR__, $ioName),
                    [
                        'name' => $name,
                        'entity_full_class_name' => $entityClassDetails->getFullName(),
                        'entity_class_name' => $entityClassDetails->getShortName(),
                    ]
                );
            }
        }

        $generator->writeChanges();

        $this->maker->generate($input, $io, $generator);
    }

    public static function getCommandDescription(): string
    {
        return BaseMakeEntity::getCommandDescription();
    }
}
