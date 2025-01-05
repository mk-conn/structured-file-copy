<?php

declare(strict_types=1);

namespace MkConn\Sfc\Factories;

use MkConn\Sfc\Models\CopyOptions;
use MkConn\Sfc\Models\Filter;

readonly class CopyOptionsFactory {
    public function __construct(private StrategieFactory $strategieFactory) {}

    /**
     * @param array<string>        $sortBy
     * @param array<string, mixed> $strategyOptions
     * @param array<Filter>        $included
     * @param array<Filter>        $excluded
     */
    public function create(
        string $source,
        string $target,
        array $sortBy = [],
        array $strategyOptions = [],
        array $included = [],
        array $excluded = [],
        bool $preserveTimestamps = false,
        bool $overwrite = false,
        bool $dryRun = false
    ): CopyOptions {
        return new CopyOptions(
            $source,
            $target,
            $this->strategieFactory->create($sortBy, $strategyOptions),
            $included,
            $excluded,
            $preserveTimestamps,
            $overwrite,
            $dryRun
        );
    }
}
