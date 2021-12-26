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
use Symfony\Component\Uid\Uuid;

final class UuidTypeConverter implements TypeConverterInterface
{
    public function fromGraphQL($value)
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException(sprintf('Expected string, got %s', gettype($value)));
        }

        return Uuid::fromString($value);
    }

    public function toGraphQL($value)
    {
        if (!$value instanceof Uuid) {
            throw new \InvalidArgumentException(
                sprintf('Expected instance of %s, got %s', Uuid::class, gettype($value))
            );
        }

        return $value->toRfc4122();
    }
}
