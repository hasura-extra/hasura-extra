<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Tests\Field;

use GraphQL\GraphQL;
use Hasura\GraphQLiteBridge\Tests\TestCase;
use TheCodingMachine\GraphQLite\Context\Context;

final class AuthorizationMiddlewareTest extends TestCase
{
    public function testUnauthorized(): void
    {
        $schema = $this->schemaFactory->createSchema();

        $result = GraphQL::executeQuery($schema, 'query { dummy }', contextValue: new Context());

        $this->assertNotEmpty($result->errors);
        $this->assertCount(1, $result->errors);

        $this->assertSame(
            'Your current role not have permission to access this field',
            $result->errors[0]->getMessage()
        );
    }

    public function testAuthorized(): void
    {
        $schema = $this->schemaFactory->createSchema();

        $result = GraphQL::executeQuery($schema, 'query { allow }', contextValue: new Context());

        $this->assertEmpty($result->errors);
        $this->assertArrayHasKey('allow', $result->data);
        $this->assertSame('allow', $result->data['allow']);
    }
}