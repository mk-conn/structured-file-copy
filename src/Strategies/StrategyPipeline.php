<?php

declare(strict_types=1);

namespace MkConn\Sfc\Strategies;

use Illuminate\Support\Collection;
use MkConn\Sfc\Strategies\Copy\CopyStrategyInterface;
use Symfony\Component\Finder\Finder;

class StrategyPipeline {
    /**
     * @var array<CopyStrategyInterface>
     */
    private array $strategyPipe = [];

    public function __construct(private readonly AvailableStrategies $strategyCollection) {}

    /**
     * @param array<string, mixed> ...$args
     */
    public function add(CopyStrategyInterface $strategy, array ...$args): static {
        $this->strategyPipe[] = $this->strategyCollection->getStrategies()->get($strategy);

        return $this;
    }

    public function run(Finder $files, string $sourcePath, Collection $copyFiles): void {
        foreach ($this->strategyPipe as $strategy) {
            $strategy->collectFiles($files, $sourcePath, $copyFiles, $this);
        }
    }
}
