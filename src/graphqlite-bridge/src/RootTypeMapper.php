<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge;

use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\NamedType;
use GraphQL\Type\Definition\OutputType;
use GraphQL\Type\Definition\ScalarType;
use Hasura\GraphQLiteBridge\ScalarType\Date;
use Hasura\GraphQLiteBridge\ScalarType\Json;
use Hasura\GraphQLiteBridge\ScalarType\Jsonb;
use Hasura\GraphQLiteBridge\ScalarType\Timestamptz;
use Hasura\GraphQLiteBridge\ScalarType\Timetz;
use Hasura\GraphQLiteBridge\ScalarType\Uuid;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Object_;
use TheCodingMachine\GraphQLite\Mappers\Root\RootTypeMapperInterface;

final class RootTypeMapper implements RootTypeMapperInterface
{
    public function __construct(private RootTypeMapperInterface $next)
    {
    }

    public function toGraphQLOutputType(Type $type, ?OutputType $subType, $reflector, DocBlock $docBlockObj): OutputType
    {
        if (!$type instanceof Object_) {
            return $this->next->toGraphQLOutputType($type, $subType, $reflector, $docBlockObj);
        }

        $scalarType = $this->getTypeByClass((string)$type->getFqsen());

        if (null === $scalarType) {
            return $this->next->toGraphQLOutputType($type, $subType, $reflector, $docBlockObj);
        }

        return $scalarType;
    }

    public function toGraphQLInputType(
        Type $type,
        ?InputType $subType,
        string $argumentName,
        $reflector,
        DocBlock $docBlockObj
    ): InputType {
        if (!$type instanceof Object_) {
            return $this->next->toGraphQLInputType($type, $subType, $argumentName, $reflector, $docBlockObj);
        }

        $scalarType = $this->getTypeByClass((string)$type->getFqsen());

        if (null === $scalarType) {
            return $this->next->toGraphQLInputType($type, $subType, $argumentName, $reflector, $docBlockObj);
        }

        return $scalarType;
    }

    public function mapNameToType(string $typeName): NamedType
    {
        return match ($typeName) {
            Date::NAME => Date::getInstance(),
            Json::NAME => Json::getInstance(),
            Jsonb::NAME => Jsonb::getInstance(),
            Timestamptz::NAME => Timestamptz::getInstance(),
            Timetz::NAME => Timetz::getInstance(),
            Uuid::NAME => Uuid::getInstance(),
            default => $this->next->mapNameToType($typeName)
        };
    }

    protected function getTypeByClass(string $class): ?ScalarType
    {
        if (str_starts_with($class, '\Symfony\Component\Uid\Uuid')) {
            return Uuid::getInstance();
        }

        return null;
    }
}
