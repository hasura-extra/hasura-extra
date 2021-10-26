<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\ApiClient;

final class ConfigApi extends AbstractApi
{
    public function get(): array
    {
        return $this->request('GET')->toArray();
    }

    protected function apiPath(): string
    {
        return '/v1alpha1/config';
    }
}
