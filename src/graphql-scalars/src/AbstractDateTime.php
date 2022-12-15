<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLScalars;

use GraphQL\Error\Error;
use GraphQL\Error\InvariantViolation;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;

abstract class AbstractDateTime extends ScalarType
{
    final public function serialize($value): string
    {
        if (!$value instanceof \DateTimeImmutable) {
            throw new InvariantViolation(
                sprintf('Value: `%s` is not an instance of %s', Utils::printSafe($value), \DateTimeImmutable::class)
            );
        }

        return $value->format($this->getSerializeFormat());
    }

    abstract protected function getSerializeFormat(): string;

    abstract protected function getParseFormat(): string;

    final public function parseValue($value): \DateTimeImmutable
    {
        if ($value instanceof \DateTimeImmutable) {
            return \DateTimeImmutable::createFromFormat($this->getParseFormat(), $this->serialize($value));
        }

        return $this->normalizeValue($value);
    }

    final public function parseLiteral($valueNode, ?array $variables = null): \DateTimeImmutable
    {
        if (!$valueNode instanceof StringValueNode) {
            throw new Error('Query error: Can only parse strings got: ' . $valueNode->kind, [$valueNode]);
        }

        return $this->normalizeValue($valueNode->value);
    }

    private function normalizeValue(mixed $value): \DateTimeImmutable
    {
        if (is_string($value)) {
            $dateTime = \DateTimeImmutable::createFromFormat($this->getParseFormat(), $value);

            if ($dateTime) {
                return $dateTime;
            }
        }

        throw new Error(
            sprintf(
                'Cannot represent following value as %s: %s',
                $this->name,
                Utils::printSafe($value),
            )
        );
    }
}
