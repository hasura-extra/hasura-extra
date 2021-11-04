<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Attribute;

use TheCodingMachine\GraphQLite\Annotations\MiddlewareAnnotationInterface;

#[\Attribute(\Attribute::TARGET_METHOD)]
final class Roles implements MiddlewareAnnotationInterface
{
    private array $names;

    public function __construct(string ...$names)
    {
        if (empty($names)) {
            throw new \InvalidArgumentException('Role names is required!');
        }

        $this->names = $names;
    }

    public function getNames(): array
    {
        return $this->names;
    }
}
