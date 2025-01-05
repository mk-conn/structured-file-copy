<?php

declare(strict_types=1);

namespace MkConn\Sfc\Strategies;

use Illuminate\Support\Collection;
use InvalidArgumentException;
use MkConn\Sfc\Enums\SortOption;
use MkConn\Sfc\Strategies\Copy\CopyStrategyInterface;

readonly class AvailableStrategies {
    /**
     * @param Collection<string, CopyStrategyInterface> $strategies
     */
    public function __construct(private Collection $strategies) {}

    /**
     * @return Collection<string, CopyStrategyInterface>
     */
    public function getStrategies(): Collection {
        return $this->strategies;
    }

    public function strategyForSortOption(SortOption $sortOption): CopyStrategyInterface {
        if (($strategy = $this->strategies->get($sortOption->value)) === null) {
            throw new InvalidArgumentException("No strategy found for sort option: $sortOption->value");
        }

        return $strategy;
    }
}
