<?php

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
        'inherited_roles' => ['manager' => ['leader']],
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
            \Hasura\Laravel\Tests\Fixture\App\Hasura\AuthenticatedSessionVariableEnhancer::class
        ]
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
        ]
    ],
    'sailor' => [
        'executor_path' => app_path('GraphQLExecutors'),
        'executor_namespace' => 'Hasura\Laravel\Tests\Fixture\App\GraphQLExecutors',
        'schema_path' => base_path('hasura/schema.graphql'),
        'query_spec_path' => base_path('hasura/graphql'),
    ]
];