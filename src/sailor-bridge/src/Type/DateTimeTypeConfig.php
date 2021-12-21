<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\SailorBridge\Type;

use Hasura\SailorBridge\Converter\DateTypeConverter;
use Hasura\SailorBridge\Converter\TimestamptzTypeConverter;
use Hasura\SailorBridge\Converter\TimetzTypeConverter;
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
            'timetz' => TimetzTypeConverter::class,
            'timestamptz' => TimestamptzTypeConverter::class,
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