<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\GraphQLite\Parameter;

use Hasura\GraphQLiteBridge\Parameter\ParameterUtils;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Type;
use ReflectionParameter;
use TheCodingMachine\GraphQLite\Annotations\ParameterAnnotations;
use TheCodingMachine\GraphQLite\Mappers\Parameters\ParameterHandlerInterface;
use TheCodingMachine\GraphQLite\Parameters\ParameterInterface;
use TheCodingMachine\GraphQLite\Validator\Mappers\Parameters\AssertParameterMiddleware;
use TheCodingMachine\GraphQLite\Validator\Mappers\Parameters\ParameterValidator;

/**
 * Extends base class to support arg naming.
 */
final class AssertionMiddleware extends AssertParameterMiddleware
{
    public function mapParameter(
        ReflectionParameter $refParameter,
        DocBlock $docBlock,
        ?Type $paramTagType,
        ParameterAnnotations $parameterAnnotations,
        ParameterHandlerInterface $next
    ): ParameterInterface {
        $parameter = parent::mapParameter($refParameter, $docBlock, $paramTagType, $parameterAnnotations, $next);

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

                return new AssertionNaming(
                    $argNamingParameter->getName(),
                    $argNamingParameter->getArgName(),
                    $parameter
                );
            }
        }

        return $parameter;
    }
}
