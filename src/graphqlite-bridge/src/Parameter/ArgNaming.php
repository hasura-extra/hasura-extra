<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Parameter;

use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\ResolveInfo;
use TheCodingMachine\GraphQLite\Parameters\InputTypeParameterInterface;

final class ArgNaming implements ArgNamingParameterInterface
{
    use ArgNamingParameterTrait;

    public function __construct(
        string $name,
        string $argName,
        InputTypeParameterInterface $parameter
    ) {
        $this->name = $name;
        $this->argName = $argName;
        $this->parameter = $parameter;
    }

    public function getType(): InputType
    {
        return $this->parameter->getType();
    }

    public function hasDefaultValue(): bool
    {
        return $this->parameter->hasDefaultValue();
    }

    public function getDefaultValue()
    {
        return $this->parameter->getDefaultValue();
    }

    protected function doResolve(?object $source, array $args, $context, ResolveInfo $info)
    {
        return $this->parameter->resolve($source, $args, $context, $info);
    }
}
