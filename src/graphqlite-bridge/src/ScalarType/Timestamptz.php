<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLiteBridge\ScalarType;

final class Timestamptz extends AbstractDateTime
{
    public const NAME = 'timestamptz';

    protected function getFormat(): string
    {
        return DATE_ISO8601;
    }
}
