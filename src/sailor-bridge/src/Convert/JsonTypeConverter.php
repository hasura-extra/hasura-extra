<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\SailorBridge\Convert;

use Spawnia\Sailor\Convert\TypeConverter as TypeConverterInterface;

final class JsonTypeConverter implements TypeConverterInterface
{
    public function fromGraphQL($value)
    {
        if (!is_array($value) && !is_object($value)) {
            throw new \InvalidArgumentException('Expected array or object, got ' . gettype($value));
        }

        return $value;
    }

    public function toGraphQL($value)
    {
        if (!is_array($value) && !is_object($value)) {
            throw new \InvalidArgumentException('Expected array or object, got ' . gettype($value));
        }

        return $value;
    }
}
