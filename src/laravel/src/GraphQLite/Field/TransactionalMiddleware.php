<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\GraphQLite\Field;

use GraphQL\Type\Definition\FieldDefinition;
use Hasura\Laravel\GraphQLite\Attribute\Transactional;
use Illuminate\Database\ConnectionResolverInterface;
use TheCodingMachine\GraphQLite\Middlewares\FieldHandlerInterface;
use TheCodingMachine\GraphQLite\Middlewares\FieldMiddlewareInterface;
use TheCodingMachine\GraphQLite\QueryFieldDescriptor;

final class TransactionalMiddleware implements FieldMiddlewareInterface
{
    public function __construct(private ConnectionResolverInterface $connectionResolver)
    {
    }

    public function process(
        QueryFieldDescriptor $queryFieldDescriptor,
        FieldHandlerInterface $fieldHandler
    ): ?FieldDefinition {
        $annotations = $queryFieldDescriptor->getMiddlewareAnnotations();
        /** @var Transactional $transactional */
        $transactional = $annotations->getAnnotationByType(Transactional::class);

        if (null === $transactional) {
            return $fieldHandler->handle($queryFieldDescriptor);
        }

        $connResolver = $this->connectionResolver;
        $resolver = $queryFieldDescriptor->getResolver();

        $queryFieldDescriptor->setResolver(
            static function (...$args) use ($resolver, $connResolver, $transactional) {
                $conn = $transactional->getConnection();
                $connResolver->connection($conn)->beginTransaction();

                try {
                    $result = call_user_func($resolver, ...$args);

                    $connResolver->connection($conn)->commit();

                    return $result;
                } catch (\Throwable $e) {
                    $connResolver->connection($conn)->rollBack();

                    throw $e;
                }
            }
        );

        return $fieldHandler->handle($queryFieldDescriptor);
    }
}
