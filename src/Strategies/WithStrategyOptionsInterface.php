<?php

declare(strict_types=1);

namespace MkConn\Sfc\Strategies;

interface WithStrategyOptionsInterface {
    /**
     * @param array<string, mixed> $options
     */
    public function withOptions(array $options): static;

    /**
     * @return array<string, array{'description': string, 'default': mixed}>|null
     */
    public function availableOptions(): ?array;
}
