<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\GraphQLite\Parameter;

use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\ResolveInfo;
use Hasura\GraphQLiteBridge\Parameter\ArgNamingParameterInterface;
use Hasura\GraphQLiteBridge\Parameter\ArgNamingParameterTrait;
use Hasura\GraphQLiteBridge\Parameter\WrappingParameterInterface;
use Hasura\GraphQLiteBridge\Parameter\WrappingParameterTrait;
use TheCodingMachine\GraphQLite\Laravel\Mappers\Parameters\ParameterValidator;

final class ValidateNaming implements ArgNamingParameterInterface, WrappingParameterInterface
{
    use ArgNamingParameterTrait;
    use WrappingParameterTrait;

    public function __construct(
        string $name,
        string $argName,
        ParameterValidator $parameter
    ) {
        $this->name = $name;
        $this->argName = $argName;
        $this->parameter = $parameter;
    }

    protected function doResolve(?object $source, array $args, $context, ResolveInfo $info)
    {
        return $this->parameter->resolve($source, $args, $context, $info);
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
}