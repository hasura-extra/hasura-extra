<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Bundle\GraphQLite\Parameter;

use Doctrine\ORM\Mapping\MappingException;
use Doctrine\Persistence\ManagerRegistry;
use Hasura\Bundle\GraphQLite\Attribute\ArgEntity as ArgEntityAttribute;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Type;
use TheCodingMachine\GraphQLite\Annotations\ParameterAnnotations;
use TheCodingMachine\GraphQLite\GraphQLRuntimeException;
use TheCodingMachine\GraphQLite\Mappers\Parameters\ParameterHandlerInterface;
use TheCodingMachine\GraphQLite\Mappers\Parameters\ParameterMiddlewareInterface;
use TheCodingMachine\GraphQLite\Parameters\ParameterInterface;

final class ArgEntityMiddleware implements ParameterMiddlewareInterface
{
    public function __construct(private ManagerRegistry $registry)
    {
    }

    public function mapParameter(
        \ReflectionParameter $parameter,
        DocBlock $docBlock,
        ?Type $paramTagType,
        ParameterAnnotations $parameterAnnotations,
        ParameterHandlerInterface $next
    ): ParameterInterface {
        /** @var ArgEntityAttribute $attribute */
        $attribute = $parameterAnnotations->getAnnotationByType(ArgEntityAttribute::class);

        if (null === $attribute) {
            return $next->mapParameter($parameter, $docBlock, $paramTagType, $parameterAnnotations);
        }

        $parameterType = $parameter->getType();

        if (!$parameterType instanceof \ReflectionNamedType) {
            throw new GraphQLRuntimeException(sprintf('Parameter `%s` is not supported by %s', $parameter->getName(), ArgEntityAttribute::class));
        }

        $em = $this->registry->getManager($attribute->getEntityManager());
        $entityClass = $parameterType->getName();

        if (!$em->getMetadataFactory()->isTransient($entityClass)) {
            $metadata = $em->getClassMetadata($entityClass);

            try {
                $isIdentifierField = $metadata->getSingleIdentifierFieldName() === $attribute->getFieldName();
            } catch (MappingException) {
                $isIdentifierField = false;
            }

            return new ArgEntity(
                $em->getRepository($parameterType->getName()),
                $parameter->getName(),
                $attribute->getArgName(),
                $attribute->getFieldName(),
                $attribute->getInputType(),
                $parameter->allowsNull(),
                $isIdentifierField
            );
        } else {
            throw new GraphQLRuntimeException(sprintf('`%s` is not an entity class!', $parameterType->getName()));
        }
    }
}
