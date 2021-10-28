<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\EventDispatcher;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Implements by classes help to dispatch events triggered by Hasura via HTTP server request.
 */
interface ServerRequestEventDispatcher
{
    public function dispatch(ServerRequestInterface $request): void;
}