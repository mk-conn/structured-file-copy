<?php

declare(strict_types=1);

namespace MkConn\Sfc\Factories;

use MkConn\Sfc\Enums\SortOption;
use MkConn\Sfc\Strategies\AvailableStrategies;
use MkConn\Sfc\Strategies\Copy\CopyStrategyInterface;

readonly class StrategieFactory {
    public function __construct(private AvailableStrategies $availableStrategies) {}

    /**
     * @param array<string>        $sortBy
     * @param array<string, mixed> $strategyOptions
     *
     * @return array<CopyStrategyInterface> $sortBy
     */
    public function create(array $sortBy = [], array $strategyOptions = []): array {
        $strategies = [];

        if (empty($sortBy)) {
            return [$this->availableStrategies->strategyForSortOption(SortOption::DEFAULT)];
        }

        foreach ($sortBy as $sort) {
            $strategy = $this->availableStrategies->strategyForSortOption(SortOption::from($sort));

            if (null !== $strategy->availableOptions()) {
                $strategy = $strategy->withOptions($strategyOptions);
            }

            $strategies[] = $strategy;
        }

        return $strategies;
    }
}
