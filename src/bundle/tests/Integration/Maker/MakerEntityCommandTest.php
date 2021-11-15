<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Integration\Maker;

use Hasura\Bundle\Tests\Integration\ConsoleTestCase;
use Symfony\Component\Console\Exception\MissingInputException;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;

final class MakerEntityCommandTest extends ConsoleTestCase
{
    protected function tearDown(): void
    {
        (new Filesystem())->remove($this->projectDir . '/src/Entity/Test');
        (new Filesystem())->remove($this->projectDir . '/src/GraphQL/Test');
        (new Filesystem())->remove($this->projectDir . '/src/Repository');

        parent::tearDown();
    }

    public function testMake(): void
    {
        $command = $this->application->get('make:entity');
        $tester = new CommandTester($command);

        $tester->execute(['name' => 'Test\\Test1']);

        $this->assertStringContainsString('Add GraphQL insert/update mutations', $tester->getDisplay());

        $this->assertFileDoesNotExist($this->projectDir . '/src/GraphQL/Test/UpdateMutation/Resolver.php');
        $this->assertFileDoesNotExist($this->projectDir . '/src/GraphQL/Test/InsertMutation/Resolver.php');
    }

    public function testMakeWithGraphQLOption(): void
    {
        $this->assertFileDoesNotExist($this->projectDir . '/src/GraphQL/Test/Test2/UpdateMutation/Resolver.php');
        $this->assertFileDoesNotExist($this->projectDir . '/src/GraphQL/Test/Test2/UpdateMutation/Input.php');
        $this->assertFileDoesNotExist($this->projectDir . '/src/GraphQL/Test/Test2/UpdateMutation/Output.php');
        $this->assertFileDoesNotExist($this->projectDir . '/src/GraphQL/Test/Test2/InsertMutation/Resolver.php');
        $this->assertFileDoesNotExist($this->projectDir . '/src/GraphQL/Test/Test2/InsertMutation/Input.php');
        $this->assertFileDoesNotExist($this->projectDir . '/src/GraphQL/Test/Test2/InsertMutation/Output.php');

        $command = $this->application->get('make:entity');
        $tester = new CommandTester($command);

        try {
            $tester->execute(['name' => 'Test\\Test2', '--graphql' => true], ['interaction' => false]);
        } catch (MissingInputException) {
        }

        $this->assertFileExists($this->projectDir . '/src/GraphQL/Test/Test2/UpdateMutation/Resolver.php');
        $this->assertFileExists($this->projectDir . '/src/GraphQL/Test/Test2/UpdateMutation/Input.php');
        $this->assertFileExists($this->projectDir . '/src/GraphQL/Test/Test2/UpdateMutation/Output.php');
        $this->assertFileExists($this->projectDir . '/src/GraphQL/Test/Test2/InsertMutation/Resolver.php');
        $this->assertFileExists($this->projectDir . '/src/GraphQL/Test/Test2/InsertMutation/Input.php');
        $this->assertFileExists($this->projectDir . '/src/GraphQL/Test/Test2/InsertMutation/Output.php');

        $this->assertStringContainsString(
            <<<'CONTENT'
declare(strict_types=1);

namespace Hasura\Bundle\Tests\Fixture\App\GraphQL\Test\Test2\UpdateMutation;

use Hasura\Bundle\Tests\Fixture\App\Entity\Test\Test2;
use Hasura\Bundle\GraphQLite\Attribute\ArgEntity;
use Hasura\Bundle\GraphQLite\Attribute\ObjectAssertion;
use Hasura\Bundle\GraphQLite\Attribute\Transactional;
use Hasura\GraphQLiteBridge\Attribute\ArgNaming;
use TheCodingMachine\GraphQLite\Annotations as GQL;

final class Resolver
{
    #[GQL\Mutation(name: 'test_test2_update', outputType: 'test_test2_update_mutation_output!')]
    #[ArgEntity(for: 'instance', inputType: 'ID!')]
    #[ArgNaming(for: 'inputObj', name: 'input_obj')]
    #[ObjectAssertion(for: 'inputObj')]
    #[Transactional]
    public function __invoke(Test2 $instance, Input $inputObj): Test2
    {

    }
}
CONTENT,
            file_get_contents($this->projectDir . '/src/GraphQL/Test/Test2/UpdateMutation/Resolver.php')
        );

        $this->assertStringContainsString(
            <<<'CONTENT'
declare(strict_types=1);

namespace Hasura\Bundle\Tests\Fixture\App\GraphQL\Test\Test2\UpdateMutation;

use TheCodingMachine\GraphQLite\Annotations as GQL;

#[GQL\Input(name: 'test_test2_update_mutation_input', default: true)]
final class Input
{
    #[GQL\Field(name: 'sample')]
    public string $sample;
}
CONTENT,
            file_get_contents($this->projectDir . '/src/GraphQL/Test/Test2/UpdateMutation/Input.php')
        );

        $this->assertStringContainsString(
            <<<'CONTENT'
declare(strict_types=1);

namespace Hasura\Bundle\Tests\Fixture\App\GraphQL\Test\Test2\UpdateMutation;

use Hasura\Bundle\Tests\Fixture\App\Entity\Test\Test2;
use TheCodingMachine\GraphQLite\Annotations as GQL;

#[GQL\Type(class: Test2::class, name: 'test_test2_update_mutation_output', default: false)]
#[GQL\SourceField(name: 'id', outputType: 'ID!')]
final class Output
{
}
CONTENT,
            file_get_contents($this->projectDir . '/src/GraphQL/Test/Test2/UpdateMutation/Output.php')
        );

        $this->assertStringContainsString(
            <<<'CONTENT'
declare(strict_types=1);

namespace Hasura\Bundle\Tests\Fixture\App\GraphQL\Test\Test2\InsertMutation;

use Hasura\Bundle\Tests\Fixture\App\Entity\Test\Test2;
use Hasura\Bundle\GraphQLite\Attribute\ObjectAssertion;
use Hasura\Bundle\GraphQLite\Attribute\Transactional;
use Hasura\GraphQLiteBridge\Attribute\ArgNaming;
use TheCodingMachine\GraphQLite\Annotations as GQL;

final class Resolver
{
    #[GQL\Mutation(name: 'test_test2_insert', outputType: 'test_test2_insert_mutation_output!')]
    #[ArgNaming(for: 'inputObj', name: 'input_obj')]
    #[ObjectAssertion(for: 'inputObj')]
    #[Transactional]
    public function __invoke(Input $inputObj): Test2
    {

    }
}
CONTENT,
            file_get_contents($this->projectDir . '/src/GraphQL/Test/Test2/InsertMutation/Resolver.php')
        );

        $this->assertStringContainsString(
            <<<'CONTENT'
declare(strict_types=1);

namespace Hasura\Bundle\Tests\Fixture\App\GraphQL\Test\Test2\InsertMutation;

use TheCodingMachine\GraphQLite\Annotations as GQL;

#[GQL\Input(name: 'test_test2_insert_mutation_input', default: true)]
final class Input
{
    #[GQL\Field(name: 'sample')]
    public string $sample;
}
CONTENT,
            file_get_contents($this->projectDir . '/src/GraphQL/Test/Test2/InsertMutation/Input.php')
        );

        $this->assertStringContainsString(
            <<<'CONTENT'
declare(strict_types=1);

namespace Hasura\Bundle\Tests\Fixture\App\GraphQL\Test\Test2\InsertMutation;

use Hasura\Bundle\Tests\Fixture\App\Entity\Test\Test2;
use TheCodingMachine\GraphQLite\Annotations as GQL;

#[GQL\Type(class: Test2::class, name: 'test_test2_insert_mutation_output', default: false)]
#[GQL\SourceField(name: 'id', outputType: 'ID!')]
final class Output
{
}
CONTENT,
            file_get_contents($this->projectDir . '/src/GraphQL/Test/Test2/InsertMutation/Output.php')
        );
    }
}