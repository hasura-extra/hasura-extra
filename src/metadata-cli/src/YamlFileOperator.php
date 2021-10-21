<?php
/*
 * (c) Minh Vuong <vuongxuongminh@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

declare(strict_types=1);

namespace Hasura\MetadataCli;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Tag\TaggedValue;
use Symfony\Component\Yaml\Yaml;

use function Symfony\Component\String\u;

final class YamlFileOperator implements FileOperatorInterface
{
    public const SOURCES = 'sources.yaml';

    public const ALLOW_LIST = 'allow_list.yaml';

    public const REST_ENDPOINTS = 'rest_endpoints.yaml';

    public const QUERY_COLLECTION = 'query_collections.yaml';

    public const REMOTE_SCHEMAS = 'remote_schemas.yaml';

    public const VERSION = 'version.yaml';

    public const INHERITED_ROLES = 'inherited_roles.yaml';

    public const ACTIONS = 'actions.yaml';

    public const CRON_TRIGGERS = 'cron_triggers.yaml';

    public const CUSTOM_TYPES = 'custom_types.yaml';

    public const FIELDS_MAPPING = [
        'version' => self::VERSION,
        'sources' => self::SOURCES,
        'allowlist' => self::ALLOW_LIST,
        'remote_schemas' => self::REMOTE_SCHEMAS,
        'actions' => self::ACTIONS,
        'cron_triggers' => self::CRON_TRIGGERS,
        'inherited_roles' => self::INHERITED_ROLES,
        'query_collections' => self::QUERY_COLLECTION,
        'rest_endpoints' => self::REST_ENDPOINTS,
        'custom_types' => self::CUSTOM_TYPES,
    ];

    public function __construct(private Filesystem $filesystem)
    {
    }

    public function export(array $metadata, string $metadataPath, bool $force = false): void
    {
        $this->ensureMetadataPath($metadataPath);

        if ($force) {
            $this->filesystem->remove($metadataPath);
            $this->filesystem->mkdir($metadataPath);
        }

        foreach (self::FIELDS_MAPPING as $field => $file) {
            if (!isset($metadata[$field])) {
                $this->filesystem->remove(sprintf('%s/%s', $metadataPath, $file));

                continue;
            }

            $exportMethod = u('export_' . $field)->camel()->toString();
            $this->{$exportMethod}($metadata[$field], $metadataPath, $file);
        }
    }

    private function exportSources(array $sources, string $basePath, string $file): void
    {
        $exported = [];

        foreach ($sources as $source) {
            $sourcePath = sprintf('sources/%s/tables', $source['name']);
            $collectionFile = sprintf('%s.yaml', $sourcePath);

            $this->exportItems(
                $source['tables'],
                fn(array $table) => sprintf(
                    '%s_%s.yaml',
                    u($table['table']['schema'])->snake()->toString(),
                    u($table['table']['name'])->snake()->toString()
                ),
                $collectionFile,
                $sourcePath,
                $basePath
            );

            $source['tables'] = $this->createIncludeTaggedValue($collectionFile);
            $exported[] = $source;
        }

        $this->filesystem->dumpFile(
            sprintf('%s/%s', $basePath, $file),
            $this->yamlDump($exported)
        );
    }

    private function exportActions(array $actions, string $basePath, string $file): void
    {
        $this->exportItems(
            $actions,
            fn(array $item) => sprintf('%s.yaml', u($item['name'])->snake()->toString()),
            $file,
            'actions',
            $basePath
        );
    }

    private function exportVersion(int $version, string $basePath, string $file): void
    {
        $this->filesystem->dumpFile(
            sprintf('%s/%s', $basePath, $file),
            $this->yamlDump($version)
        );
    }

    private function exportCustomTypes(array $customTypes, string $basePath, string $file): void
    {
        $exported = [];

        foreach ($customTypes as $type => $items) {
            $typePath = sprintf('custom_types/%s', $type);
            $collectionFilePath = sprintf('%s.yaml', $typePath);
            $exported[$type] = $this->createIncludeTaggedValue($collectionFilePath);

            $this->exportItems(
                $items,
                fn(array $item) => sprintf('%s.yaml', u($item['name'])->snake()->toString()),
                $collectionFilePath,
                $typePath,
                $basePath
            );
        }

        $this->filesystem->dumpFile(
            sprintf('%s/%s', $basePath, $file),
            $this->yamlDump($exported)
        );
    }

    private function exportCronTriggers(array $cronTriggers, string $basePath, string $file): void
    {
        $this->exportItems(
            $cronTriggers,
            fn(array $item) => sprintf('%s.yaml', u($item['name'])->snake()->toString()),
            $file,
            'cron_triggers',
            $basePath
        );
    }

    private function exportRemoteSchemas(array $remoteSchemas, string $basePath, string $file): void
    {
        $this->exportItems(
            $remoteSchemas,
            fn(array $item) => sprintf('%s.yaml', u($item['name'])->snake()->toString()),
            $file,
            'remote_schemas',
            $basePath
        );
    }

    private function exportRestEndpoints(array $restEndpoints, string $basePath, string $file): void
    {
        $this->exportItems(
            $restEndpoints,
            fn(array $item) => sprintf('%s.yaml', u($item['name'])->snake()->toString()),
            $file,
            'rest_endpoints',
            $basePath
        );
    }

    private function exportAllowlist(array $allowList, string $basePath, string $file): void
    {
        $this->filesystem->dumpFile(
            sprintf(
                '%s/%s',
                $basePath,
                $file
            ),
            $this->yamlDump($allowList)
        );
    }

    private function exportInheritedRoles(array $inheritedRoles, string $basePath, string $file): void
    {
        $this->exportItems(
            $inheritedRoles,
            fn(array $item) => sprintf('%s.yaml', u($item['role_name'])->snake()->toString()),
            $file,
            'inherited_roles',
            $basePath
        );
    }

    private function exportQueryCollections(array $queryCollections, string $basePath, string $file): void
    {
        $this->exportItems(
            $queryCollections,
            fn(array $item) => sprintf('%s.yaml', u($item['name'])->snake()->toString()),
            $file,
            'query_collections',
            $basePath
        );
    }

    private function exportItems(
        array $items,
        callable $itemNameGenerator,
        string $collectionFile,
        string $itemsPath,
        string $basePath
    ): void {
        $itemsPath = sprintf('%s/%s', $basePath, $itemsPath);
        $collectionFile = sprintf('%s/%s', $basePath, $collectionFile);
        $exported = [];

        $this->filesystem->mkdir($itemsPath);

        foreach ($items as $item) {
            $file = $itemNameGenerator($item);
            $relativePath = rtrim($this->filesystem->makePathRelative($itemsPath, dirname($collectionFile)), '/');
            $relativeFilePath = sprintf('%s/%s', $relativePath, $file);
            $exported[] = $this->createIncludeTaggedValue($relativeFilePath);

            $this->filesystem->dumpFile(
                sprintf('%s/%s', $itemsPath, $file),
                $this->yamlDump($item)
            );
        }

        $this->filesystem->dumpFile(
            $collectionFile,
            $this->yamlDump($exported)
        );
    }

    private function yamlDump(mixed $data): string
    {
        $flags = Yaml::DUMP_NULL_AS_TILDE | Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK | Yaml::DUMP_EMPTY_ARRAY_AS_SEQUENCE | Yaml::DUMP_OBJECT_AS_MAP;

        return Yaml::dump($data, 10, flags: $flags);
    }

    private function createIncludeTaggedValue(string $file): TaggedValue
    {
        return new TaggedValue('include', $file);
    }

    private function ensureMetadataPath(string $path, bool $needWritable = true): void
    {
        $this->filesystem->mkdir($path);

        if (!is_writable($path) && $needWritable) {
            throw new \InvalidArgumentException('Metadata path should have write permission.');
        } elseif (!is_readable($path)) {
            throw new \InvalidArgumentException('Metadata path should have read permission.');
        }
    }

    public function load(string $metadataPath): array
    {
        $this->ensureMetadataPath($metadataPath);

        $metadata = [];

        foreach (self::FIELDS_MAPPING as $field => $file) {
            $file = sprintf('%s/%s', $metadataPath, $file);

            if (!$this->filesystem->exists($file)) {
                continue;
            }

            $metadata[$field] = $this->parseYamlFile($file);
        }

        return $metadata;
    }

    private function parseYamlFile(string $file): mixed
    {
        $value = Yaml::parseFile($file, Yaml::PARSE_CUSTOM_TAGS | Yaml::PARSE_OBJECT_FOR_MAP);
        $inPath = dirname($file);

        return $this->parseValue($value, $inPath);
    }

    private function parseValue(mixed $value, string $inPath): mixed
    {
        if ($value instanceof \stdClass && ($arrayValue = get_object_vars($value))) {
            $value = $arrayValue;
        }

        if ($value instanceof TaggedValue) {
            $value = $this->parseTaggedValue($value, $inPath);
        }

        if (is_array($value)) {
            foreach ($value as &$item) {
                $item = $this->parseValue($item, $inPath);
            }
        }

        return $value;
    }

    private function parseTaggedValue(TaggedValue $value, string $inPath): mixed
    {
        if ('include' !== $value->getTag()) {
            throw new \RuntimeException('Only support include tag, tag: `%s` is not support', $value->getTag());
        }

        $file = sprintf('%s/%s', $inPath, $value->getValue());

        return $this->parseYamlFile($file);
    }
}