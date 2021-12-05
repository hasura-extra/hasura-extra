<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

$router = app('router');
$config = config('hasura.routes.auth_hook');
$name = $config['name'] ?? 'hasura-auth-hook';
$uri = $config['uri'] ?? '/hasura-auth-hook';

unset($config['name'], $config['uri']);

$action = array_merge(
    $config,
    [
        'as' => $name,
        'uses' => 'Hasura\AuthHook\RequestHandler@handle',
    ]
);

$router->get($uri, $action);
