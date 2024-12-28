<?php

declare(strict_types=1);

namespace MkConn\Sfc\Strategies;

use MkConn\Sfc\Strategies\Copy\CopyStrategyInterface;

abstract class CopyStrategy implements CopyStrategyInterface {
    protected ?CopyStrategyInterface $nextStrategy = null;

    public function withNextStrategy(CopyStrategyInterface $nextStrategy): static {
        $clone = clone $this;
        $clone->nextStrategy = $nextStrategy;

        return $clone;
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    public function withOptions(array $options): static {
        return $this;
    }

    public function availableOptions(): ?array {
        return null;
    }
}
