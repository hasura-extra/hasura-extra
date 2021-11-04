<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Parameter;

use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Type;
use TheCodingMachine\GraphQLite\Annotations\ParameterAnnotations;
use TheCodingMachine\GraphQLite\Mappers\Parameters\ParameterHandlerInterface;
use TheCodingMachine\GraphQLite\Mappers\Parameters\ParameterMiddlewareInterface;
use TheCodingMachine\GraphQLite\Parameters\InputTypeParameterInterface;
use TheCodingMachine\GraphQLite\Parameters\ParameterInterface;

final class AvoidExplicitDefaultNullMiddleware implements ParameterMiddlewareInterface
{
    public function mapParameter(
        \ReflectionParameter $parameter,
        DocBlock $docBlock,
        ?Type $paramTagType,
        ParameterAnnotations $parameterAnnotations,
        ParameterHandlerInterface $next
    ): ParameterInterface {
        $mapped = $next->mapParameter($parameter, $docBlock, $paramTagType, $parameterAnnotations);

        if (!$mapped instanceof InputTypeParameterInterface) {
            return $mapped;
        }

        return new AvoidExplicitDefaultNull($mapped);
    }
}
