<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\GraphQLite\Parameter;

use Hasura\GraphQLiteBridge\Parameter\ParameterUtils;
use Hasura\Laravel\GraphQLite\Attribute\ValidateObject as Attribute;
use Illuminate\Contracts\Validation\Factory;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Type;
use ReflectionParameter;
use TheCodingMachine\GraphQLite\Annotations\ParameterAnnotations;
use TheCodingMachine\GraphQLite\GraphQLRuntimeException;
use TheCodingMachine\GraphQLite\Mappers\Parameters\ParameterHandlerInterface;
use TheCodingMachine\GraphQLite\Mappers\Parameters\ParameterMiddlewareInterface;
use TheCodingMachine\GraphQLite\Parameters\InputTypeParameterInterface;
use TheCodingMachine\GraphQLite\Parameters\ParameterInterface;

final class ValidateObjectMiddleware implements ParameterMiddlewareInterface
{
    public function __construct(private Factory $factory)
    {
    }

    public function mapParameter(
        ReflectionParameter $parameter,
        DocBlock $docBlock,
        ?Type $paramTagType,
        ParameterAnnotations $parameterAnnotations,
        ParameterHandlerInterface $next
    ): ParameterInterface {
        $attribute = $parameterAnnotations->getAnnotationByType(Attribute::class);
        $parameterMapped = $next->mapParameter($parameter, $docBlock, $paramTagType, $parameterAnnotations);

        if (null === $attribute) {
            return $parameterMapped;
        }

        $parameterType = $parameter->getType();

        if (!$parameterType instanceof \ReflectionNamedType || !class_exists($parameterType->getName())) {
            throw new GraphQLRuntimeException(
                sprintf('`%s` attribute only support object parameter', Attribute::class)
            );
        }

        if (!$parameterMapped instanceof InputTypeParameterInterface) {
            throw new GraphQLRuntimeException(sprintf('`%s` attribute only support input parameter', Attribute::class));
        }

        $argNamingParameter = ParameterUtils::getArgNamingParameter($parameterMapped);
        $atPath = $argNamingParameter ? $argNamingParameter->getArgName() : $parameter->getName();

        return new ValidateObject(
            $this->factory,
            $atPath,
            $attribute->getCustomErrorArgumentNames(),
            $parameterMapped
        );
    }
}
