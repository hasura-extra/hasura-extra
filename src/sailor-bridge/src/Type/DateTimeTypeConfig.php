<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\SailorBridge\Type;

use Hasura\SailorBridge\Convert\DateTypeConverter;
use Hasura\SailorBridge\Convert\TimestampTypeConverter;
use Hasura\SailorBridge\Convert\TimestamptzTypeConverter;
use Hasura\SailorBridge\Convert\TimeTypeConverter;
use Hasura\SailorBridge\Convert\TimetzTypeConverter;
use Spawnia\Sailor\Type\TypeConfig as TypeConfigInterface;

final class DateTimeTypeConfig implements TypeConfigInterface
{
    public function __construct(private string $typeName)
    {
    }

    public function typeConverter(): string
    {
        return match ($name = $this->typeName) {
            'date' => DateTypeConverter::class,
            'timestamp' => TimestampTypeConverter::class,
            'timestamptz' => TimestamptzTypeConverter::class,
            'time' => TimeTypeConverter::class,
            'timetz' => TimetzTypeConverter::class,
            default => throw new \RuntimeException(sprintf('Not found type converter for `%s` type', $name))
        };
    }

    public function typeReference(): string
    {
        return \DateTimeInterface::class;
    }

    public function generateClasses(): iterable
    {
        return [];
    }
}
