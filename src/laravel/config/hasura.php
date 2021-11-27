<?php

return [
    /*
     * Hasura base uri
     */
    'base_uri' => env('HASURA_BASE_URI', 'http://hasura:8080'),
    /*
     * Hasura admin secret
     */
    'admin_secret' => env('HASURA_ADMIN_SECRET'),
    /*
     * Application remote schema name had added on Hasura.
     */
    'remote_schema_name' => null,
    'auth' => [
        /*
         * Illuminate auth guard use to authenticate user via webhook mode,
         * default null mean with use `auth.defaults.guard` config.
         */
        'guard' => null,
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
            \Hasura\Metadata\ReloadStateProcessor::class,
            \Hasura\GraphQLiteBridge\RemoteSchemaPermissionStateProcessor::class
        ]
    ],
    'sailor' => [
        'executor_path' => app_path('GraphQLExecutors'),
        'executor_namespace' => 'App\GraphQLExecutors',
        'schema_path' => base_path('hasura/schema.graphql'),
        'query_spec_path' => base_path('hasura/graphql'),
    ]
];