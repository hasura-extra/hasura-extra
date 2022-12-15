<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLScalars;

final class Timestamptz extends AbstractDateTime
{
    public $name = 'timestamptz';

    protected function getParseFormat(): string
    {
        return \DateTimeInterface::ATOM;
    }

    protected function getSerializeFormat(): string
    {
        return \DateTimeInterface::ATOM;
    }
}
