<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\Tests\Integration\Sailor;

use Hasura\Bundle\Tests\Integration\ConsoleTestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class CodegenCommandTest extends ConsoleTestCase
{
    use SailorPathTrait;

    public function testCodegen(): void
    {
        // First we need to introspect
        $introspectCommand = $this->getCommand('hasura:sailor:introspect');
        $introspectCommandTester = new CommandTester($introspectCommand);
        $introspectCommandTester->execute([]);

        // Then add query spec to generate code
        $query = /** @lang GraphQL */ <<<'GQL'
query Articles {
    articles: _flattenmany_article {
        id
        title
    }
}
GQL;
        file_put_contents($this->querySpecPath .'/article.graphql', $query);

        $codegenCommand = $this->getCommand('hasura:sailor:codegen');
        $codegenCommandTester = new CommandTester($codegenCommand);
        $codegenCommandTester->execute([]);

        $this->assertStringContainsString('[OK] Generated successfully!', $codegenCommandTester->getDisplay());

        // Check can generated Articles class
        $articlesClass = self::getContainer()->getParameter('hasura.sailor.executor_namespace') . '\Articles';

        $this->assertTrue(class_exists($articlesClass));

        // And can execute query
        $articles = $articlesClass::execute();

        $this->assertGreaterThan(0, count($articles->data->articles));
    }
}