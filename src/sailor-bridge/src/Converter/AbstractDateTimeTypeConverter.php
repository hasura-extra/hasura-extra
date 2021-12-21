<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\SailorBridge\Converter;

use Spawnia\Sailor\Convert\TypeConverter as TypeConverterInterface;

abstract class AbstractDateTimeTypeConverter implements TypeConverterInterface
{
    abstract protected function getFormat(): string;

    public function fromGraphQL($value)
    {
        try {
            $dateTime = \DateTimeImmutable::createFromFormat($this->getFormat(), $value);
        } catch (\Throwable) {
            $dateTime = false;
        }

        if (false === $dateTime) {
            throw new \InvalidArgumentException(
                sprintf('Expected string with format `%s`, got %s', $this->getFormat(), gettype($value))
            );
        }

        return $dateTime;
    }

    public function toGraphQL($value)
    {
        if (!$value instanceof \DateTimeInterface) {
            throw new \InvalidArgumentException(
                sprintf('Expected instance of %s, got %s', \DateTimeInterface::class, gettype($value))
            );
        }

        return $value->format($this->getFormat());
    }
}