<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\Tests\GraphQLite\Field;

use Hasura\Laravel\GraphQLite\Attribute\Transactional;
use Hasura\Laravel\GraphQLite\Field\TransactionalMiddleware;
use Hasura\Laravel\Tests\TestCase;
use TheCodingMachine\GraphQLite\Annotations\MiddlewareAnnotations;
use TheCodingMachine\GraphQLite\Middlewares\FieldHandlerInterface;
use TheCodingMachine\GraphQLite\QueryFieldDescriptor;

final class TransactionalMiddlewareTest extends TestCase
{
    public function testSkipProcessInCasesNotHaveAttribute(): void
    {
        $queryFieldDescriptor = $this->createMock(QueryFieldDescriptor::class);
        $queryFieldDescriptor->expects($this->exactly(0))->method('getResolver');
        $queryFieldDescriptor->expects($this->exactly(0))->method('setResolver');

        $handle = $this->createMock(FieldHandlerInterface::class);
        $handle->expects($this->once())->method('handle');

        $middleware = new TransactionalMiddleware($this->app['db']);
        $middleware->process($queryFieldDescriptor, $handle);
    }

    public function testProcess(): void
    {
        $queryFieldDescriptor = new QueryFieldDescriptor();
        $queryFieldDescriptor->setResolver('var_dump');
        $queryFieldDescriptor->setMiddlewareAnnotations(new MiddlewareAnnotations([new Transactional()]));

        $handle = $this->createMock(FieldHandlerInterface::class);
        $handle->expects($this->once())->method('handle');

        $middleware = new TransactionalMiddleware($this->app['db']);
        $middleware->process($queryFieldDescriptor, $handle);

        $this->assertNotSame('var_dump', $queryFieldDescriptor->getResolver());
    }
}