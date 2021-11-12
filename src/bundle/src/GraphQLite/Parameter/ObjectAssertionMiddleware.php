<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\GraphQLite\Parameter;

use Hasura\Bundle\GraphQLite\Attribute\ObjectAssertion as ObjectAssertionAttribute;
use Hasura\Bundle\GraphQLite\ObjectAssertion\Executor;
use Hasura\GraphQLiteBridge\Parameter\ParameterUtils;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Type;
use TheCodingMachine\GraphQLite\Annotations\ParameterAnnotations;
use TheCodingMachine\GraphQLite\GraphQLRuntimeException;
use TheCodingMachine\GraphQLite\Mappers\Parameters\ParameterHandlerInterface;
use TheCodingMachine\GraphQLite\Mappers\Parameters\ParameterMiddlewareInterface;
use TheCodingMachine\GraphQLite\Parameters\InputTypeParameterInterface;
use TheCodingMachine\GraphQLite\Parameters\ParameterInterface;

final class ObjectAssertionMiddleware implements ParameterMiddlewareInterface
{
    public function __construct(private Executor $executor)
    {
    }

    public function mapParameter(
        \ReflectionParameter $parameter,
        DocBlock $docBlock,
        ?Type $paramTagType,
        ParameterAnnotations $parameterAnnotations,
        ParameterHandlerInterface $next
    ): ParameterInterface {
        $parameterMapped = $next->mapParameter($parameter, $docBlock, $paramTagType, $parameterAnnotations);

        /** @var ObjectAssertionAttribute $attribute */
        $attribute = $parameterAnnotations->getAnnotationByType(ObjectAssertionAttribute::class);

        if (null === $attribute) {
            return $parameterMapped;
        }

        $parameterType = $parameter->getType();

        if (!$parameterType instanceof \ReflectionNamedType || !class_exists($parameterType->getName())) {
            throw new GraphQLRuntimeException(sprintf('`%s` attribute only support object parameter', ObjectAssertion::class));
        }

        if (!$parameterMapped instanceof InputTypeParameterInterface) {
            throw new GraphQLRuntimeException(sprintf('`%s` attribute only support input parameter', ObjectAssertion::class));
        }

        if (!($attribute->getMode() & ObjectAssertionAttribute::BEFORE_RESOLVE_CALL)) {
            return $parameterMapped;
        }

        $argNamingParameter = ParameterUtils::getArgNamingParameter($parameterMapped);
        $atPath = $argNamingParameter ? $argNamingParameter->getArgName() : $parameter->getName();

        return new ObjectAssertion(
            $this->executor,
            $atPath,
            $attribute->getCustomViolationPropertyPaths(),
            $attribute->getGroups(),
            $parameterMapped
        );
    }
}
