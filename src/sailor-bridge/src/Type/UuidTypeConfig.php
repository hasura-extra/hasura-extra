<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\SailorBridge\Type;

use Hasura\SailorBridge\Converter\UuidTypeConverter;
use Spawnia\Sailor\Type\TypeConfig as TypeConfigInterface;
use Symfony\Component\Uid\Uuid;

final class UuidTypeConfig implements TypeConfigInterface
{
    public function typeConverter(): string
    {
        return UuidTypeConverter::class;
    }

    public function typeReference(): string
    {
        return Uuid::class;
    }

    public function generateClasses(): iterable
    {
        return [];
    }
}