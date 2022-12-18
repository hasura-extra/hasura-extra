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
use GraphQL\Language\AST\BooleanValueNode;
use GraphQL\Language\AST\FloatValueNode;
use GraphQL\Language\AST\IntValueNode;
use GraphQL\Language\AST\ListValueNode;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\NullValueNode;
use GraphQL\Language\AST\ObjectValueNode;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Language\AST\VariableNode;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;

abstract class AbstractJson extends ScalarType
{
    public function serialize($value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if ($value instanceof \JsonSerializable) {
            return $value->jsonSerialize();
        }

        throw new InvariantViolation(sprintf('Value: `%s` is not a %s', Utils::printSafe($value), $this->name));
    }

    public function parseValue($value): array
    {
        if (!is_array($value)) {
            throw new Error(
                sprintf(
                    'Cannot represent following value as %s: %s',
                    $this->name,
                    Utils::printSafe($value)
                )
            );
        }

        return $value;
    }

    public function parseLiteral($valueNode, ?array $variables = null): array
    {
        if (!$valueNode instanceof ListValueNode && !$valueNode instanceof ObjectValueNode) {
            throw new Error('Query error: Can only parse list or object got: ' . $valueNode->kind, [$valueNode]);
        }

        return $this->parseJsonValueNode($valueNode, $variables);
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
            return (int)$node->value;
        }

        if ($node instanceof FloatValueNode) {
            return (float)$node->value;
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
