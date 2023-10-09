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
use Symfony\Component\Uid\Uuid as SymfonyUuid;

final class Uuid extends ScalarType
{
    public string $name = 'uuid';

    public function serialize($value): string
    {
        if (!$value instanceof SymfonyUuid) {
            throw new InvariantViolation(
                sprintf('Value `%s` is not an instance of %s', Utils::printSafe($value), SymfonyUuid::class)
            );
        }

        return $value->toRfc4122();
    }

    public function parseValue($value): SymfonyUuid
    {
        if ($value instanceof SymfonyUuid) {
            return $value;
        }

        return $this->normalizeValue($value);
    }

    public function parseLiteral($valueNode, ?array $variables = null): SymfonyUuid
    {
        if (!$valueNode instanceof StringValueNode) {
            throw new Error('Query error: Can only parse strings got: ' . $valueNode->kind, [$valueNode]);
        }

        return $this->normalizeValue($valueNode->value);
    }

    private function normalizeValue(mixed $value): SymfonyUuid
    {
        try {
            return SymfonyUuid::fromString($value);
        } catch (\Throwable) {
            throw new Error(
                sprintf(
                    'Cannot represent following value as %s: %s',
                    $this->name,
                    Utils::printSafe($value),
                )
            );
        }
    }
}
