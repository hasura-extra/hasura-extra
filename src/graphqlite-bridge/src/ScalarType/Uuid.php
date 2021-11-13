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
use Symfony\Component\Uid\Uuid as SymfonyUuid;

final class Uuid extends AbstractScalar
{
    public const NAME = 'uuid';

    public function __construct()
    {
        if (!class_exists(SymfonyUuid::class)) {
            throw new \RuntimeException('You need to be install symfony/uid Composer package to use `uuid` type!');
        }

        parent::__construct();
    }

    public function serialize($value)
    {
        if (!$value instanceof SymfonyUuid) {
            throw new InvariantViolation(
                sprintf('Value `%s` is not an instance of %s', Utils::printSafe($value), SymfonyUuid::class)
            );
        }

        return $value->toRfc4122();
    }

    public function parseValue($value)
    {
        if ($value instanceof SymfonyUuid) {
            return $value;
        }

        try {
            return SymfonyUuid::fromString($value);
        } catch (\Throwable) {
            throw new Error(
                sprintf(
                    'Cannot represent following value as %s: %s',
                    self::NAME,
                    Utils::printSafe($value),
                )
            );
        }
    }

    public function parseLiteral($valueNode, ?array $variables = null)
    {
        if (!$valueNode instanceof StringValueNode) {
            throw new Error('Query error: Can only parse strings got: ' . $valueNode->kind, [$valueNode]);
        }

        return $valueNode->value;
    }
}
