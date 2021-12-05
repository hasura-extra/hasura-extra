<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Laravel\ServiceProvider\Concerns;

use Hasura\AuthHook\AccessRoleDeciderInterface;
use Hasura\AuthHook\ChainSessionVariableEnhancer;
use Hasura\AuthHook\RequestHandler;
use Hasura\AuthHook\SessionVariableEnhancerInterface;
use Hasura\Laravel\Auth\GateRoleChecker;
use Hasura\Laravel\Auth\InheritanceRole;
use Hasura\Laravel\AuthHook\AccessRoleDecider;
use Hasura\Laravel\Contract\InheritanceRole as InheritanceRoleContract;
use Hasura\Laravel\ServiceProvider\HasuraServiceProvider;
use Illuminate\Auth\GenericUser;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Http\Request;
use Nyholm\Psr7\Factory\Psr17Factory;

/**
 * @mixin HasuraServiceProvider
 */
trait Auth
{
    private function bootAuth(): void
    {
        if (config('hasura.auth.enabled_role_check_method')) {
            $this->app[Gate::class]->before(
                static fn (?object $user, iterable|string $abilities) => app(GateRoleChecker::class)->check(
                    $user,
                    $abilities
                )
            );
        }

        $this->app['auth']->viaRequest('hasura', $this->hasuraGuard());
    }

    private function hasuraGuard(): callable
    {
        return function (Request $request) {
            if ('hasura' === $request->getUser() && config('hasura.app_secret') === $request->getPassword()) {
                return new GenericUser(
                    [
                        'id' => 'hasura',
                        'password' => $request->getPassword(),
                        'remember_token' => '',
                    ]
                );
            }

            return null;
        };
    }

    private function registerAuth(): void
    {
        config(
            [
                'auth.guards.hasura' => array_merge(
                    [
                        'driver' => 'hasura',
                        'provider' => null,
                    ],
                    config('auth.guards.hasura', [])
                ),
            ]
        );

        $this->app->singleton(
            'hasura.gate',
            static function ($app) {
                $guards = config('hasura.auth.guard') ?? [null];
                $user = null;

                foreach ($guards as $guard) {
                    $user = $app['auth']->guard($guard)->user();

                    if (null !== $user) {
                        break;
                    }
                }

                return $app[Gate::class]->forUser($user);
            }
        );

        $this->app->singleton(
            AccessRoleDecider::class,
            static fn ($app) => new AccessRoleDecider(
                config('hasura.auth.anonymous_role'),
                config('hasura.auth.default_role'),
                $app['hasura.gate']
            )
        );
        $this->app->bind(AccessRoleDeciderInterface::class, AccessRoleDecider::class);

        $this->app->singleton(
            ChainSessionVariableEnhancer::class,
            static fn ($app) => new ChainSessionVariableEnhancer(
                collect(config('hasura.auth.session_variable_enhancers'))->map(
                    static fn (string $class) => $app[$class]
                )
            )
        );
        $this->app->bind(SessionVariableEnhancerInterface::class, ChainSessionVariableEnhancer::class);

        $this->app->singleton(
            RequestHandler::class,
            static fn ($app) => new RequestHandler(
                $app[AccessRoleDeciderInterface::class],
                new Psr17Factory(),
                new Psr17Factory(),
                $app[SessionVariableEnhancerInterface::class]
            )
        );

        $this->app->singleton(
            InheritanceRole::class,
            static fn ($app) => new InheritanceRole(config('hasura.auth.inherited_roles'))
        );
        $this->app->bind(InheritanceRoleContract::class, InheritanceRole::class);

        $this->app->singleton(
            GateRoleChecker::class,
            static fn ($app) => new GateRoleChecker(
                config('hasura.auth.anonymous_role'),
                $app[InheritanceRoleContract::class]
            )
        );
    }
}
