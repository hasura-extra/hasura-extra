<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLScalars;

use GraphQL\Language\AST\Node;

final class Float8 extends Numeric
{
    public $name = 'float8';

    public function serialize($value): float
    {
        return (float)parent::serialize($value);
    }

    public function parseValue($value): float
    {
        return (float)parent::parseValue($value);
    }

    public function parseLiteral(Node $valueNode, ?array $variables = null): float
    {
        return (float)parent::parseLiteral($valueNode, $variables);
    }
}