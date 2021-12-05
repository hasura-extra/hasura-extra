<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\Integration\Sailor;

use Hasura\Laravel\Tests\TestCase;
use Symfony\Component\Filesystem\Filesystem;

final class CodegenCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        (new Filesystem())->mkdir(config('hasura.sailor.query_spec_path'));
        (new Filesystem())->remove(config('hasura.sailor.schema_path'));
    }

    protected function tearDown(): void
    {
        (new Filesystem())->remove(config('hasura.sailor.query_spec_path'));
        (new Filesystem())->remove(config('hasura.sailor.schema_path'));

        parent::tearDown();
    }

    public function testCodegen(): void
    {
        $query = /** @lang GraphQL */
            <<<'GQL'
query Articles {
    articles: _flattenmany_article {
        id
        title
    }
}
GQL;
        file_put_contents(config('hasura.sailor.query_spec_path') . '/articles.graphql', $query);

        $this->artisan('hasura:sailor:introspect')->run();
        $tester = $this->artisan('hasura:sailor:codegen');
        $tester->run();

        $tester->assertSuccessful();

        $articlesClass = config('hasura.sailor.executor_namespace') . '\\Articles';

        $this->assertTrue(class_exists($articlesClass));

        // And can execute query
        $articles = $articlesClass::execute();

        $this->assertGreaterThan(0, count($articles->data->articles));
    }
}
