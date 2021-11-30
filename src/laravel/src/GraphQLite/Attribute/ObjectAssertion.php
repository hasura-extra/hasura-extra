<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\GraphQLite\Attribute;

use TheCodingMachine\GraphQLite\Annotations\ParameterAnnotationInterface;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final class ObjectAssertion implements ParameterAnnotationInterface
{
    public function __construct(
        private string $for,
        private ?array $customErrorArgumentNames = null
    ) {
        $this->for = ltrim($this->for, '$');
    }

    public function getCustomErrorArgumentNames(): ?array
    {
        return $this->customErrorArgumentNames;
    }

    public function getTarget(): string
    {
        return $this->for;
    }
}