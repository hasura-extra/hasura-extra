<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\Metadata;

final class MetadataUtils
{
    public static function normalizeMetadata(array $metadata): array
    {
        $metadataNormalized = [];

        foreach ($metadata as $field => $value) {
            $metadataNormalized[$field] = self::normalizeEmptyObjectFieldValue($field, $value);
        }

        return $metadataNormalized;
    }

    private static function normalizeEmptyObjectFieldValue(string $field, mixed $value): mixed
    {
        if (!is_array($value)) {
            return $value;
        }

        static $objectFieldPatterns = [
            '~^sources\.\d+\.tables\.\d+\.(select|insert|update|delete)_permissions\.\d+\.permission\.(check|filter)$~',
            '~^sources\.\d+\.tables\.\d+\.event_triggers\.\d+\.request_transform\.query_params$~',
            '~^remote_schemas\.\d+\.definition\.customization\.(type_names|field_names\.\d+)\.mapping$~',
            '~^actions\.\d+\.definition\.request_transform\.query_params$~',
        ];

        foreach ($value as $childField => &$childValue) {
            $childValue = self::normalizeEmptyObjectFieldValue(
                sprintf('%s.%s', $field, $childField),
                $childValue
            );
        }

        if (!empty($value)) {
            return $value;
        }

        foreach ($objectFieldPatterns as $pattern) {
            if (preg_match($pattern, $field)) {
                $value = new \ArrayObject($value);
                break;
            }
        }

        return $value;
    }
}
