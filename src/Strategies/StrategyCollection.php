<?php

declare(strict_types=1);

namespace MkConn\Sfc\Strategies;

use Illuminate\Support\Collection;
use InvalidArgumentException;
use MkConn\Sfc\Strategies\Copy\CopyStrategyInterface;

readonly class StrategyCollection {
    /**
     * @param Collection<array-key, CopyStrategyInterface> $strategies
     */
    public function __construct(private Collection $strategies) {}

    /**
     * @return Collection<array-key, CopyStrategyInterface>
     */
    public function getStrategies(): Collection {
        return $this->strategies;
    }

    public function strategy(string $strategy): CopyStrategyInterface {
        if (!$this->strategies->has($strategy)) {
            throw new InvalidArgumentException("Strategy $strategy not found");
        }

        return $this->strategies->get($strategy);
    }
}
