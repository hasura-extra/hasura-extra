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
use Hasura\GraphQLiteBridge\Attribute\Roles;
use TheCodingMachine\GraphQLite\Middlewares\FieldHandlerInterface;
use TheCodingMachine\GraphQLite\Middlewares\FieldMiddlewareInterface;
use TheCodingMachine\GraphQLite\Middlewares\MissingAuthorizationException;
use TheCodingMachine\GraphQLite\QueryFieldDescriptor;
use TheCodingMachine\GraphQLite\Security\AuthorizationServiceInterface;

final class AuthorizationMiddleware implements FieldMiddlewareInterface
{
    public function __construct(private AuthorizationServiceInterface $authorizationService)
    {
    }

    public function process(
        QueryFieldDescriptor $queryFieldDescriptor,
        FieldHandlerInterface $fieldHandler
    ): ?FieldDefinition {
        $annotations = $queryFieldDescriptor->getMiddlewareAnnotations();
        /** @var Roles $rolesAttribute */
        $rolesAttribute = $annotations->getAnnotationByType(Roles::class);

        if (null !== $rolesAttribute) {
            $resolver = $this->wrapResolverAuthorization($rolesAttribute->getNames(), $queryFieldDescriptor->getResolver());
            $queryFieldDescriptor->setResolver($resolver);
        }

        return $fieldHandler->handle($queryFieldDescriptor);
    }

    private function wrapResolverAuthorization(array $acceptRoles, callable $resolver): callable
    {
        $authorizationService = $this->authorizationService;

        return static function (...$args) use ($authorizationService, $acceptRoles, $resolver) {
            $isUnauthorized = true;

            foreach ($acceptRoles as $role) {
                if ($authorizationService->isAllowed($role)) {
                    $isUnauthorized = false;
                    break;
                }
            }

            if ($isUnauthorized) {
                throw MissingAuthorizationException::forbidden();
            }

            return call_user_func($resolver, ...$args);
        };
    }
}
