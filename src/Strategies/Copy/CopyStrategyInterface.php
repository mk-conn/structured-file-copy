<?php

declare(strict_types=1);

namespace MkConn\Sfc\Strategies\Copy;

use Illuminate\Support\Collection;
use MkConn\Sfc\Models\CopyFile;
use MkConn\Sfc\Strategies\WithStrategyOptionsInterface;
use Symfony\Component\Finder\Finder;

interface CopyStrategyInterface extends WithStrategyOptionsInterface {
    /**
     * @param Collection<array-key, CopyFile> $copyFiles
     */
    public function collectFiles(Finder $files, string $targetPath, Collection $copyFiles): void;

    public function withNextStrategy(CopyStrategyInterface $nextStrategy): static;
}
