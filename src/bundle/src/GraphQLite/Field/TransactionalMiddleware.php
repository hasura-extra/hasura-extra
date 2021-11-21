<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\GraphQLite\Field;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\Proxy;
use GraphQL\Type\Definition\FieldDefinition;
use Hasura\Bundle\GraphQLite\Attribute\Transactional;
use TheCodingMachine\GraphQLite\Middlewares\FieldHandlerInterface;
use TheCodingMachine\GraphQLite\Middlewares\FieldMiddlewareInterface;
use TheCodingMachine\GraphQLite\QueryFieldDescriptor;

final class TransactionalMiddleware implements FieldMiddlewareInterface
{
    public function __construct(private ?ManagerRegistry $registry)
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

        if (null === $this->registry) {
            throw new \LogicException(
                'The DoctrineBundle is not registered in your application. Try running "composer require symfony/orm-pack".'
            );
        }

        $resolver = $queryFieldDescriptor->getResolver();
        $em = $this->registry->getManager($transactional->getEntityManager());

        $queryFieldDescriptor->setResolver(
            static function (...$args) use ($resolver, $em, $transactional) {
                $em->beginTransaction();

                try {
                    $result = call_user_func($resolver, ...$args);

                    if ($transactional->isAutoPersist() && is_object($result)) {
                        $entityClass = $result instanceof Proxy ? get_parent_class($result) : get_class($result);

                        if (!$em->getMetadataFactory()->isTransient($entityClass)) {
                            $em->persist($result);
                        }
                    }

                    $em->flush();
                    $em->commit();

                    return $result;
                } catch (\Throwable $e) {
                    $em->close();
                    $em->rollBack();

                    throw $e;
                }
            }
        );

        return $fieldHandler->handle($queryFieldDescriptor);
    }
}
