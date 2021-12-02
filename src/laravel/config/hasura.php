<?php

return [
    /*
     * Hasura base uri.
     */
    'base_uri' => env('HASURA_BASE_URI', 'http://hasura:8080'),
    /*
     * Hasura admin secret.
     */
    'admin_secret' => env('HASURA_ADMIN_SECRET'),
    /*
     * App secret will use to identifier Hasura webhook requests (actions, events triggered).
     */
    'app_secret' => env('APP_HASURA_SECRET', '!ChangeMe!'),
    /*
     * Application remote schema name had added on Hasura.
     */
    'remote_schema_name' => null,
    'auth' => [
        /*
         * When set to true, the method for checking roles will be registered on the gate.
         * Set this to false, if you want to implement custom logic for checking roles.
         */
        'enabled_role_check_method' => true,
        /*
         * An array names of auth guards or `null` used to get current user for authenticate request,
         * Default `null` mean with use `auth.defaults.guard` config.
         */
        'guard' => null,
        /*
         * Defines inherited roles will use authorize checking and persist to Hasura inherited roles.
         */
        'inherited_roles' => [],
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
        'session_variable_enhancers' => []
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
            // If you want to add the remote schema permission state processor below, please make sure you had config `remote_schema_name`.
            // \Hasura\GraphQLiteBridge\RemoteSchemaPermissionStateProcessor::class,
            \Hasura\Metadata\InheritedRolesStateProcessor::class,
        ]
    ],
    'sailor' => [
        /*
         * Path store executor classes generated.
         */
        'executor_path' => app_path('GraphQLExecutors'),
        /*
         * Namespace of executor classes generated.
         */
        'executor_namespace' => 'App\GraphQLExecutors',
        /*
         * Path store Hasura SDL when run `hasura:sailor:introspect` command.
         */
        'schema_path' => base_path('hasura/schema.graphql'),
        /*
         * Path store your GraphQL queries spec.
         */
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
             * Set of route middleware, please not use `auth` middleware because this route will handle both user and anonymous requests.
             */
            'middleware' => []
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
            'middleware' => ['auth:hasura']
        ]
    ]
];