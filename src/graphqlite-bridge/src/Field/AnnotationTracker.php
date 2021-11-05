<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Field;

use TheCodingMachine\GraphQLite\Annotations\MiddlewareAnnotationInterface;

final class AnnotationTracker
{
    private array $queryAnnotations = [];

    private array $mutationAnnotations = [];

    public function trackQueryFieldAnnotation(MiddlewareAnnotationInterface $annotation, string $field): void
    {
        $this->queryAnnotations[$annotation::class][$field][] = $annotation;
    }

    public function trackMutationFieldAnnotation(MiddlewareAnnotationInterface $annotation, string $field)
    {
        $this->mutationAnnotations[$annotation::class][$field][] = $annotation;
    }

    public function getQueryFieldAnnotations(string $annotationClass, string $field = null): array
    {
        if (null === $field) {
            return $this->queryAnnotations[$annotationClass] ?? [];
        } else {
            return $this->queryAnnotations[$annotationClass][$field] ?? [];
        }
    }

    public function getMutationFieldAnnotations(string $annotationClass, string $field = null): array
    {
        if (null === $field) {
            return $this->mutationAnnotations[$annotationClass] ?? [];
        } else {
            return $this->mutationAnnotations[$annotationClass][$field] ?? [];
        }
    }
}