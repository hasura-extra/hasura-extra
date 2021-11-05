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
use GraphQL\Language\AST\BooleanValueNode;
use GraphQL\Language\AST\FloatValueNode;
use GraphQL\Language\AST\IntValueNode;
use GraphQL\Language\AST\ListValueNode;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\NullValueNode;
use GraphQL\Language\AST\ObjectValueNode;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Language\AST\VariableNode;
use GraphQL\Utils\Utils;
use TheCodingMachine\GraphQLite\GraphQLRuntimeException;

abstract class AbstractJson extends AbstractScalar
{
    public function serialize($value)
    {
        if (is_array($value)) {
            return $value;
        }

        if ($value instanceof \JsonSerializable) {
            return $value->jsonSerialize();
        }

        throw new InvariantViolation(sprintf('Value: `%s` is not a %s', Utils::printSafe($value), static::NAME));
    }

    public function parseValue($value)
    {
        if (is_array($value)) {
            return $value;
        }

        throw new Error(sprintf('Cannot represent following value as %s: %s', static::NAME, Utils::printSafe($value)));
    }

    public function parseLiteral($valueNode, ?array $variables = null)
    {
        if ($valueNode instanceof ListValueNode || $valueNode instanceof ObjectValueNode) {
            try {
                return $this->parseValue($this->parseJsonValueNode($valueNode, $variables));
            } catch (\Throwable) {
            }
        }

        // Intentionally without message, as all information already in wrapped Exception
        throw new GraphQLRuntimeException();
    }

    private function parseJsonValueNode(Node $node, ?array $variables = null): mixed
    {
        if ($node instanceof VariableNode) {
            return $variables[$node->name->value] ?? null;
        }

        if ($node instanceof StringValueNode || $node instanceof BooleanValueNode) {
            return $node->value;
        }

        if ($node instanceof IntValueNode) {
            return (int) $node->value;
        }

        if ($node instanceof FloatValueNode) {
            return (float) $node->value;
        }

        if ($node instanceof NullValueNode) {
            return null;
        }

        if ($node instanceof ListValueNode) {
            return array_map(
                fn ($value) => $this->parseJsonValueNode($value, $variables),
                iterator_to_array($node->values->getIterator())
            );
        }

        if ($node instanceof ObjectValueNode) {
            $value = [];

            foreach ($node->fields as $field) {
                $value[$field->name->value] = $this->parseJsonValueNode($field->value, $variables);
            }

            return $value;
        }

        throw new Error('Can not detect value node type to parse', $node);
    }
}
