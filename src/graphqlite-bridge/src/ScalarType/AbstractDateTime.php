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
use TheCodingMachine\GraphQLite\GraphQLRuntimeException;

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

        $dateTime = \DateTimeImmutable::createFromFormat($this->getFormat(), $value);

        if (false === $dateTime) {
            throw new Error(
                sprintf('Fail to parse `%s` to an instance of %s', Utils::printSafe($value), \DateTimeInterface::class)
            );
        }

        return $dateTime;
    }

    final public function parseLiteral($valueNode, ?array $variables = null)
    {
        if ($valueNode instanceof StringValueNode) {
            try {
                return $this->parseValue($valueNode->value);
            } catch (\Throwable) {
            }
        }

        // Intentionally without message, as all information already in wrapped Exception
        throw new GraphQLRuntimeException();
    }
}
