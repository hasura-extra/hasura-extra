<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Field;

use GraphQL\Type\Definition\FieldDefinition;
use TheCodingMachine\GraphQLite\Annotations\MiddlewareAnnotationInterface;
use TheCodingMachine\GraphQLite\Annotations\Mutation;
use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\GraphQLite\Middlewares\FieldHandlerInterface;
use TheCodingMachine\GraphQLite\Middlewares\FieldMiddlewareInterface;
use TheCodingMachine\GraphQLite\QueryFieldDescriptor;

final class AnnotationTrackingMiddleware implements FieldMiddlewareInterface
{
    public function __construct(private AnnotationTracker $tracker)
    {
    }

    public function process(
        QueryFieldDescriptor $queryFieldDescriptor,
        FieldHandlerInterface $fieldHandler
    ): ?FieldDefinition {
        $annotations = $queryFieldDescriptor
            ->getMiddlewareAnnotations()
            ->getAnnotationsByType(MiddlewareAnnotationInterface::class);

        if (!empty($annotations)) {
            $isQuery = !empty($queryFieldDescriptor->getRefMethod()->getAttributes(Query::class));
            $isMutation = !empty($queryFieldDescriptor->getRefMethod()->getAttributes(Mutation::class));

            foreach ($annotations as $annotation) {
                if ($isQuery) {
                    $this->tracker->trackQueryFieldAnnotation($annotation, $queryFieldDescriptor->getName());
                }

                if ($isMutation) {
                    $this->tracker->trackMutationFieldAnnotation($annotation, $queryFieldDescriptor->getName());
                }
            }
        }

        return $fieldHandler->handle($queryFieldDescriptor);
    }
}
