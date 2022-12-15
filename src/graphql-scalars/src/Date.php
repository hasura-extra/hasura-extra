<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\GraphQLScalars;

final class Date extends AbstractDateTime
{
    public $name = 'date';

    protected function getParseFormat(): string
    {
        return 'Y-m-d|';
    }

    protected function getSerializeFormat(): string
    {
        return 'Y-m-d';
    }
}
