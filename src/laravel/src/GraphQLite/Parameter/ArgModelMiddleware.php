<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\GraphQLite\Parameter;

use Hasura\Laravel\GraphQLite\Attribute\ArgModel as Attribute;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Type;
use ReflectionParameter;
use TheCodingMachine\GraphQLite\Annotations\ParameterAnnotations;
use TheCodingMachine\GraphQLite\GraphQLRuntimeException;
use TheCodingMachine\GraphQLite\Mappers\Parameters\ParameterHandlerInterface;
use TheCodingMachine\GraphQLite\Mappers\Parameters\ParameterMiddlewareInterface;
use TheCodingMachine\GraphQLite\Parameters\ParameterInterface;

final class ArgModelMiddleware implements ParameterMiddlewareInterface
{
    public function mapParameter(
        ReflectionParameter $parameter,
        DocBlock $docBlock,
        ?Type $paramTagType,
        ParameterAnnotations $parameterAnnotations,
        ParameterHandlerInterface $next
    ): ParameterInterface {
        /** @var Attribute $attribute */
        $attribute = $parameterAnnotations->getAnnotationByType(Attribute::class);

        if (null === $attribute) {
            return $next->mapParameter($parameter, $docBlock, $paramTagType, $parameterAnnotations);
        }

        $parameterType = $parameter->getType();

        if (!$parameterType instanceof \ReflectionNamedType) {
            throw new GraphQLRuntimeException(
                sprintf('Parameter `%s` is not supported by %s', $parameter->getName(), Attribute::class)
            );
        }

        $modelClass = $parameterType->getName();

        if (!is_a($modelClass, Model::class, true)) {
            throw new GraphQLRuntimeException(
                sprintf('Type of `%s` parameter should be subclass of Eloquent model.', $parameterType->getName())
            );
        }

        return new ArgModel(
            $modelClass,
            $parameter->getName(),
            $attribute->getArgName(),
            $attribute->getFieldName(),
            $attribute->getInputType(),
            $parameter->allowsNull(),
        );
    }
}
