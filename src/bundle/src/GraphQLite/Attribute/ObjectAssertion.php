<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\GraphQLite\Attribute;

use Symfony\Component\Validator\Constraints\GroupSequence;
use TheCodingMachine\GraphQLite\Annotations\MiddlewareAnnotationInterface;
use TheCodingMachine\GraphQLite\Annotations\ParameterAnnotationInterface;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final class ObjectAssertion implements ParameterAnnotationInterface, MiddlewareAnnotationInterface
{
    public const BEFORE_RESOLVE_CALL = 1;

    public const AFTER_RESOLVE_CALL = 2;

    public const BEFORE_AND_AFTER_RESOLVE_CALL = self::BEFORE_RESOLVE_CALL | self::AFTER_RESOLVE_CALL;

    public function __construct(
        private string $for,
        private array | string | null | GroupSequence $groups = null,
        private int $mode = self::BEFORE_RESOLVE_CALL,
        private ?array $customViolationPropertyPaths = null
    ) {
        $this->for = ltrim($this->for, '$');
    }

    public function getCustomViolationPropertyPaths(): ?array
    {
        return $this->customViolationPropertyPaths;
    }

    public function getGroups(): array|string|null|GroupSequence
    {
        return $this->groups;
    }

    public function getTarget(): string
    {
        return $this->for;
    }

    public function getMode(): int
    {
        return $this->mode;
    }
}
