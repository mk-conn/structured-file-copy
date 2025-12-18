<?php

declare(strict_types=1);

namespace MkConn\Sfc\Factories;

use MkConn\Sfc\Enums\FilterType;
use MkConn\Sfc\Models\Filter;
use MkConn\Sfc\Services\FileService\FileTypes;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Mime\MimeTypes;

readonly class FileFinderFactory {
    public function __construct(private MimeTypes $mimeTypes) {}

    /**
     * @param array<Filter> $include
     * @param array<Filter> $exclude
     */
    public function create(string $source, array $include = [], array $exclude = []): Finder {
        $finder = (new Finder())->in($source);

        if (!empty($include)) {
            $finder = $this->addIncludes($finder, $include);
        }

        if (!empty($exclude)) {
            $finder = $this->addExcludes($finder, $exclude);
        }

        return $finder;
    }

    /**
     * @param array<Filter> $filters
     */
    private function addIncludes(Finder $finder, array $filters): Finder {
        foreach ($filters as $filter) {
            switch ($filter->filterType->name) {
                case FilterType::EXT:
                    $finder->name("*.$filter->value");

                    break;
                case FilterType::SIZE:
                    $finder->size($filter->value);

                    break;
                case FilterType::NAME:
                    $finder->name($filter->value);

                    break;
                case FilterType::FILE_TYPE:
                    $finder->name($this->mapFileTypeToMimeTypeExtensions($filter->value));

                    break;
                case FilterType::DIRECTORY:
                    $finder->in($filter->value);

                    break;
            }
        }

        return $finder;
    }

    /**
     * @param array<Filter> $exclude
     */
    private function addExcludes(Finder $finder, array $exclude): Finder {
        foreach ($exclude as $filter) {
            switch ($filter->filterType) {
                case FilterType::EXT:
                    $finder->notName("*.$filter->value");

                    break;
                case FilterType::SIZE:
                    $finder->size("$filter->value");

                    break;
                case FilterType::NAME:
                    $finder->notName("$filter->value");

                    break;
                case FilterType::FILE_TYPE:
                    break;
                case FilterType::DIRECTORY:
                    $finder->exclude($filter->value);

                    break;
            }
        }

        return $finder;
    }

    /**
     * @return array<string>
     */
    private function mapFileTypeToMimeTypeExtensions(string $fileType): array {
        $exts = [];
        $types = FileTypes::$typeMap[$fileType] ?? [];

        foreach ($types as $type) {
            $exts = array_merge($exts, array_map(fn (string $ext) => '/\.' . preg_quote($ext, '/') . '$/i', $this->mimeTypes->getExtensions($type)));
        }

        return $exts;
    }
}
