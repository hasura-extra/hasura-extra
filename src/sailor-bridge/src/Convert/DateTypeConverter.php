<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\SailorBridge\Convert;

final class DateTypeConverter extends AbstractDateTimeTypeConverter
{
    protected function getFormat(): string
    {
        return 'Y-m-d';
    }
}
