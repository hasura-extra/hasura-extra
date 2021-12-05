<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

$router = app('router');
$config = config('hasura.routes.table_event');
$name = $config['name'] ?? 'hasura-table-event';
$uri = $config['uri'] ?? '/hasura-table-event';

unset($config['name'], $config['uri']);

$action = array_merge(
    $config,
    [
        'as' => $name,
        'uses' => 'Hasura\EventDispatcher\TableEventRequestHandler@handle',
    ]
);

$router->post($uri, $action);
