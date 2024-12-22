<?php

declare(strict_types=1);

namespace MkConn\Sfc\Strategies\Copy;

use Illuminate\Support\Collection;
use MkConn\Sfc\Models\CopyFile;
use Symfony\Component\Finder\Finder;

interface CopyStrategyInterface {
    /**
     * @param Collection<array-key, CopyFile> $copyFiles
     */
    public function collectFiles(Finder $files, string $targetPath, Collection $copyFiles, ?CopyStrategyInterface $nextStrategy = null): void;
}
