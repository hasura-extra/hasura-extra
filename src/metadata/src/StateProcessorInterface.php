<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;

/**
 * This interface implements by classes will process (reload, sync remote schema role permissions, sync inherited roles, etc...)
 * from your application state to Hasura.
 */
interface StateProcessorInterface
{
    /**
     * process (sync remote schema role permissions, inherited roles, etc...) to Hasura.
     *
     * @param bool $allowInconsistent whether allow inconsistent after process.
     * @throws ClientExceptionInterface when process state to Hasura have problems.
     */
    public function process(bool $allowInconsistent = false): void;
}