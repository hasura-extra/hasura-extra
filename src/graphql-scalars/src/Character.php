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
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;

final class Character extends ScalarType
{
    public string $name = 'character';

    public function serialize($value)
    {
        return $this->normalizeValue($value, InvariantViolation::class);
    }

    public function parseValue($value)
    {
        return $this->normalizeValue($value, Error::class);
    }

    public function parseLiteral(Node $valueNode, ?array $variables = null)
    {
        if (!$valueNode instanceof StringValueNode) {
            throw new Error('Query error: Can only parse strings got: ' . $valueNode->kind, [$valueNode]);
        }

        return $this->parseValue($valueNode->value);
    }

    private function normalizeValue(mixed $value, string $exceptionClass): string
    {
        if (false === is_string($value) || 1 !== strlen($value)) {
            throw new $exceptionClass(
                sprintf('Value: `%s` must be single character string', Utils::printSafe($value))
            );
        }

        return $value;
    }
}
