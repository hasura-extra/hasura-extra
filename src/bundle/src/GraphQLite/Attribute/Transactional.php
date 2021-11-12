<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\GraphQLite\Attribute;

use TheCodingMachine\GraphQLite\Annotations\MiddlewareAnnotationInterface;

#[\Attribute(\Attribute::TARGET_METHOD)]
final class Transactional implements MiddlewareAnnotationInterface
{
    public function __construct(private bool $autoPersist = true, private ?string $entityManager = null)
    {
    }

    public function isAutoPersist(): bool
    {
        return $this->autoPersist;
    }

    public function getEntityManager(): ?string
    {
        return $this->entityManager;
    }
}
