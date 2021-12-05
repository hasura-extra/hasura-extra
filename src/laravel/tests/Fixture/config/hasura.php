<?php

declare(strict_types=1);

return [
    /*
     * Hasura base uri.
     */
    'base_uri' => env('HASURA_BASE_URI', 'http://hasura:8080'),
    /*
     * Hasura admin secret.
     */
    'admin_secret' => 'test',
    /*
     * App secret will use to identifier Hasura webhook request (actions, events triggered).
     */
    'app_secret' => 'test',
    /*
     * Application remote schema name had added on Hasura.
     */
    'remote_schema_name' => 'laravel',
    'auth' => [
        /*
         * When set to true, the method for checking roles will be registered on the gate.
         * Set this to false, if you want to implement custom logic for checking roles.
         */
        'enabled_role_check_method' => true,
        /*
         * Defines inherited roles will use authorize checking and persist to Hasura inherited roles.
         */
        'inherited_roles' => [
            'manager' => ['leader'],
        ],
        /*
         * Default role for authenticated user when user not request role via `x-hasura-role` header.
         */
        'default_role' => 'user',
        /*
         * Role for unauthenticated user.
         */
        'anonymous_role' => 'anonymous',
        /*
         * Set of enhancers implements \Hasura\AuthHook\SessionVariableEnhancerInterface support to enhance session variables of request.
         */
        'session_variable_enhancers' => [
            \Hasura\Laravel\Tests\Fixture\App\Hasura\AuthenticatedSessionVariableEnhancer::class,
        ],
    ],
    'metadata' => [
        /*
         * Path store your Hasura metadata.
         */
        'path' => base_path('hasura/metadata'),
        /*
         * Set of processors implements \Hasura\Metadata\StateProcessorInterface support to persist application state to Hasura.
         */
        'state_processors' => [
            /*
             * Hasura Extra processors should be run first.
             */
            \Hasura\Metadata\ReloadStateProcessor::class,
            \Hasura\GraphQLiteBridge\RemoteSchemaPermissionStateProcessor::class,
            \Hasura\Metadata\InheritedRolesStateProcessor::class,
        ],
    ],
    'sailor' => [
        'executor_path' => app_path('GraphQLExecutors'),
        'executor_namespace' => 'Hasura\Laravel\Tests\Fixture\App\GraphQLExecutors',
        'schema_path' => base_path('hasura/schema.graphql'),
        'query_spec_path' => base_path('hasura/graphql'),
    ],
    'routes' => [
        'auth_hook' => [
            /*
             * Enabled auth hook route, disable it when you plan to use Hasura JWT auth mode.
             */
            'enabled' => true,
            /*
             * Route uri.
             */
            'uri' => '/hasura-auth-hook',
            /*
             * Set of route middleware.
             */
            'middleware' => [],
        ],
        'table_event' => [
            /*
             * Enabled table event handle endpoint, disable it when you not use Hasura event triggered.
             */
            'enabled' => true,
            /*
             * Route uri.
             */
            'uri' => '/hasura-table-event',
            /*
             * Set of route middleware,
             * `hasura` guard will be use basic auth with fixed user `hasura` and password's `hasura.app_secret` config value above.
             */
            'middleware' => ['auth:hasura'],
        ],
    ],
];
