<?php

declare(strict_types=1);

namespace MkConn\Sfc\Models;

use MkConn\Sfc\Strategies\Copy\CopyStrategyInterface;

readonly class CopyOptions {
    /**
     * @param array<CopyStrategyInterface> $strategies
     * @param array<Filter>                $included
     * @param array<Filter>                $excluded
     */
    public function __construct(
        public string $source,
        public string $target,
        public array $strategies = [],
        public array $included = [],
        public array $excluded = [],
        public bool $preserveTimestamps = true,
        public bool $overwrite = false,
        public bool $dryRun = false,
    ) {}
}
