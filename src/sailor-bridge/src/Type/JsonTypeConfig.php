<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\SailorBridge\Type;

use Hasura\SailorBridge\Converter\JsonTypeConverter;
use Spawnia\Sailor\Type\TypeConfig as TypeConfigInterface;

final class JsonTypeConfig implements TypeConfigInterface
{
    public function typeConverter(): string
    {
        return JsonTypeConverter::class;
    }

    public function typeReference(): string
    {
        return 'array|object';
    }

    public function generateClasses(): iterable
    {
        return [];
    }
}