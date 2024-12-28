<?php

declare(strict_types=1);

namespace MkConn\Sfc\Strategies;

use Illuminate\Support\Collection;
use MkConn\Sfc\Models\CopyFile;
use MkConn\Sfc\Strategies\Copy\CopyStrategyInterface;
use Symfony\Component\Finder\Finder;

readonly class StrategyPipeline {
    /**
     * @param array<CopyStrategyInterface> $strategies
     *
     * @return Collection<array-key, CopyFile>
     */
    public function run(array $strategies, Finder $files, string $targetPath): Collection {
        $strategyChain = $this->buildStrategyChain($strategies);
        $copyFiles = collect();

        $strategyChain->collectFiles($files, $targetPath, $copyFiles);

        return $copyFiles;
    }

    /**
     * @param array<array-key, CopyStrategyInterface> $strategies
     */
    private function buildStrategyChain(array $strategies): CopyStrategyInterface {
        $count = count($strategies);
        $updatedStrategies = [];

        for ($i = $count - 1; $i >= 0; --$i) {
            $nextStrategy = $updatedStrategies[$i + 1] ?? null;

            if ($nextStrategy) {
                $updatedStrategies[$i] = $strategies[$i]->withNextStrategy($nextStrategy);
            } else {
                $updatedStrategies[$i] = $strategies[$i];
            }
        }

        return $updatedStrategies[0];
    }
}
