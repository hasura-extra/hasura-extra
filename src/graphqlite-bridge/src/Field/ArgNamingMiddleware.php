<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\Field;

use GraphQL\Type\Definition\FieldDefinition;
use Hasura\GraphQLiteBridge\Parameter\ParameterUtils;
use TheCodingMachine\GraphQLite\Middlewares\FieldHandlerInterface;
use TheCodingMachine\GraphQLite\Middlewares\FieldMiddlewareInterface;
use TheCodingMachine\GraphQLite\Parameters\InputTypeParameterInterface;
use TheCodingMachine\GraphQLite\QueryFieldDescriptor;

final class ArgNamingMiddleware implements FieldMiddlewareInterface
{
    public function process(
        QueryFieldDescriptor $queryFieldDescriptor,
        FieldHandlerInterface $fieldHandler
    ): ?FieldDefinition {
        $fieldDef = $fieldHandler->handle($queryFieldDescriptor);
        $parameters = $queryFieldDescriptor->getParameters();

        if (!empty($parameters)) {
            $fieldDefArgs = array_column($fieldDef->args, null, 'name');

            foreach ($parameters as $parameter) {
                if (!$parameter instanceof InputTypeParameterInterface) {
                    continue;
                }

                $parameter = ParameterUtils::getArgNamingParameter($parameter);

                if (null === $parameter) {
                    continue;
                }

                $fieldDefArgs[$parameter->getName()]->name = $parameter->getArgName();
            }
        }

        return $fieldDef;
    }
}
