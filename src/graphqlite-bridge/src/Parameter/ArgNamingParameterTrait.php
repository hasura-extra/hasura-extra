<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Parameter;

use GraphQL\Type\Definition\ResolveInfo;

trait ArgNamingParameterTrait
{
    private string $name;

    private string $argName;

    public function getName(): string
    {
        return $this->name;
    }

    public function getArgName(): string
    {
        return $this->argName;
    }

    final public function resolve(?object $source, array $args, $context, ResolveInfo $info)
    {
        if (array_key_exists($this->argName, $args)) {
            $args[$this->name] = $args[$this->argName];

            unset($args[$this->argName]);
        }

        return $this->doResolve($source, $args, $context, $info);
    }

    abstract protected function doResolve(?object $source, array $args, $context, ResolveInfo $info);
}
