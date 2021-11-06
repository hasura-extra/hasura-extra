<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Parameter;

use Hasura\GraphQLiteBridge\Attribute\ArgNaming as ArgNamingAttribute;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Type;
use TheCodingMachine\GraphQLite\Annotations\ParameterAnnotations;
use TheCodingMachine\GraphQLite\GraphQLRuntimeException;
use TheCodingMachine\GraphQLite\Mappers\Parameters\ParameterHandlerInterface;
use TheCodingMachine\GraphQLite\Mappers\Parameters\ParameterMiddlewareInterface;
use TheCodingMachine\GraphQLite\Parameters\InputTypeParameterInterface;
use TheCodingMachine\GraphQLite\Parameters\ParameterInterface;

final class ArgNamingMiddleware implements ParameterMiddlewareInterface
{
    use ArgNamingMiddlewareTrait;

    public function mapParameter(
        \ReflectionParameter $parameter,
        DocBlock $docBlock,
        ?Type $paramTagType,
        ParameterAnnotations $parameterAnnotations,
        ParameterHandlerInterface $next
    ): ParameterInterface {
        /** @var ArgNamingAttribute $attribute */
        $attribute = $parameterAnnotations->getAnnotationByType(ArgNamingAttribute::class);
        $parameterMapped = $next->mapParameter($parameter, $docBlock, $paramTagType, $parameterAnnotations);

        if (null === $attribute) {
            return $parameterMapped;
        }

        if (!$parameterMapped instanceof InputTypeParameterInterface) {
            throw new GraphQLRuntimeException(sprintf('`%s` attribute only support input parameter', ArgNamingAttribute::class));
        }

        $this->assertArgNamingParameterMapped($parameterMapped);

        return new ArgNaming($parameter->getName(), $attribute->getName(), $parameterMapped);
    }
}
