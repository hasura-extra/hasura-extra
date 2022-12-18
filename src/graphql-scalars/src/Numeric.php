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
use GraphQL\Language\AST\FloatValueNode;
use GraphQL\Language\AST\IntValueNode;
use GraphQL\Language\AST\Node;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;

class Numeric extends ScalarType
{
    public $name = 'numeric';

    public function serialize($value): float|int
    {
        if (is_float($value) || is_int($value)) {
            return $value;
        }

        throw new InvariantViolation(
            sprintf('Value: `%s` is not numeric', Utils::printSafe($value))
        );
    }

    public function parseValue($value): float|int
    {
        if (is_float($value) || is_int($value)) {
            return $value;
        }

        throw new Error(
            sprintf('Value: `%s` is not numeric', Utils::printSafe($value))
        );
    }

    public function parseLiteral(Node $valueNode, ?array $variables = null): float|int
    {
        if ($valueNode instanceof FloatValueNode) {
            return (float)$valueNode->value;
        }

        if ($valueNode instanceof IntValueNode) {
            return (int)$valueNode->value;
        }

        throw new Error('Query error: Can only parse numeric got: ' . $valueNode->kind, [$valueNode]);
    }
}
