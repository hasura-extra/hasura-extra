<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\ScalarType;

use GraphQL\Type\Definition\ScalarType;

abstract class AbstractScalar extends ScalarType
{
    public const NAME = '';

    public function __construct()
    {
        parent::__construct(
            [
                'name' => static::NAME,
                'description' => sprintf('A GraphQL type that can contain %s', static::NAME),
            ]
        );
    }

    final public static function getInstance(): static
    {
        static $instance;

        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }
}
