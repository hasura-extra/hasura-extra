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
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Type;
use ReflectionParameter;
use TheCodingMachine\GraphQLite\Annotations\ParameterAnnotations;
use TheCodingMachine\GraphQLite\Laravel\Mappers\Parameters\ParameterValidator;
use TheCodingMachine\GraphQLite\Laravel\Mappers\Parameters\ValidateFieldMiddleware;
use TheCodingMachine\GraphQLite\Mappers\Parameters\ParameterHandlerInterface;
use TheCodingMachine\GraphQLite\Parameters\ParameterInterface;

final class ValidateMiddleware extends ValidateFieldMiddleware
{
    public function mapParameter(
        ReflectionParameter $refParameter,
        DocBlock $docBlock,
        ?Type $paramTagType,
        ParameterAnnotations $parameterAnnotations,
        ParameterHandlerInterface $next
    ): ParameterInterface {
        $parameter = parent::mapParameter(
            $refParameter,
            $docBlock,
            $paramTagType,
            $parameterAnnotations,
            $next
        );

        if ($parameter instanceof ParameterValidator) {
            $refClass = new \ReflectionClass($parameter);
            $refProperty = $refClass->getProperty('parameter');
            $refProperty->setAccessible(true);
            $parameterWrapped = $refProperty->getValue($parameter);
            $argNamingParameter = ParameterUtils::getArgNamingParameter($parameterWrapped);

            if (null !== $argNamingParameter) {
                $refProperty = $refClass->getProperty('parameterName');
                $refProperty->setAccessible(true);
                $refProperty->setValue($parameter, $argNamingParameter->getArgName());

                return new ValidateNaming(
                    $argNamingParameter->getName(),
                    $argNamingParameter->getArgName(),
                    $parameter
                );
            }
        }

        return $parameter;
    }
}
