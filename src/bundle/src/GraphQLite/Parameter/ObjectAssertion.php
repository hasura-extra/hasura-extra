<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\GraphQLite\Parameter;

use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\ResolveInfo;
use Hasura\Bundle\GraphQLite\ObjectAssertion\Executor;
use Hasura\GraphQLiteBridge\Parameter\WrappingParameterInterface;
use Hasura\GraphQLiteBridge\Parameter\WrappingParameterTrait;
use Symfony\Component\Validator\Constraints\GroupSequence;
use TheCodingMachine\GraphQLite\Parameters\InputTypeParameterInterface;

final class ObjectAssertion implements InputTypeParameterInterface, WrappingParameterInterface
{
    use WrappingParameterTrait;

    public function __construct(
        private Executor $executor,
        private string $atPath,
        private ?array $customViolationPropertyPaths,
        private array | string | null | GroupSequence $groups,
        InputTypeParameterInterface $parameter,
    ) {
        $this->parameter = $parameter;
    }

    public function resolve(?object $source, array $args, $context, ResolveInfo $info)
    {
        $value = $this->parameter->resolve($source, $args, $context, $info);

        $this->executor->execute($value, $this->atPath, $this->customViolationPropertyPaths, $this->groups);

        return $value;
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
