<?php

declare(strict_types=1);

use GraphQL\Error\DebugFlag;

return [
    'controllers' => 'Hasura\\Laravel\\Tests\\Fixture\\App\\Http\\GraphQL\\',
    'types' => 'Hasura\\Laravel\\Tests\\Fixture\\App\\Http\\GraphQL\\',
    'debug' => DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE,
    'middleware' => ['api'],
];
