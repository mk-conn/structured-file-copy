<?php

declare(strict_types=1);

namespace MkConn\Sfc\Factories;

use MkConn\Sfc\Models\CopyOptions;
use MkConn\Sfc\Models\Filter;

class CopyOptionsFactory {
    public function __construct(private readonly StrategieFactory $strategieFactory) {}

    /**
     * @param array<string>        $sortBy
     * @param array<string, mixed> $strategyOptions
     * @param array<Filter>        $filters
     * @param array<Filter>        $excludes
     */
    public function create(
        string $source,
        string $target,
        array $sortBy = [],
        array $strategyOptions = [],
        array $filters = [],
        array $excludes = []
    ): CopyOptions {
        return new CopyOptions(
            $source,
            $target,
            $this->strategieFactory->create($sortBy, $strategyOptions),
            $filters,
            $excludes
        );
    }
}
