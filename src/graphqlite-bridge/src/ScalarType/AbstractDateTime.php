<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\ScalarType;

use GraphQL\Error\Error;
use GraphQL\Error\InvariantViolation;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Utils\Utils;

abstract class AbstractDateTime extends AbstractScalar
{
    final public function serialize($value)
    {
        if (!$value instanceof \DateTimeInterface) {
            throw new InvariantViolation(
                sprintf('Value: `%s` is not an instance of %s', Utils::printSafe($value), \DateTimeInterface::class)
            );
        }

        return $value->format($this->getFormat());
    }

    abstract protected function getFormat(): string;

    final public function parseValue($value)
    {
        if ($value instanceof \DateTimeInterface) {
            return $value;
        }

        try {
            $dateTime = \DateTimeImmutable::createFromFormat($this->getFormat(), $value);
        } catch (\Throwable) {
            $dateTime = false;
        }

        if (!$dateTime instanceof \DateTimeImmutable) {
            throw new Error(
                sprintf(
                    'Cannot represent following value as %s: %s',
                    static::NAME,
                    Utils::printSafe($value),
                )
            );
        }

        return $dateTime;
    }

    final public function parseLiteral($valueNode, ?array $variables = null)
    {
        if (!$valueNode instanceof StringValueNode) {
            throw new Error('Query error: Can only parse strings got: ' . $valueNode->kind, [$valueNode]);
        }

        return $valueNode->value;
    }
}
