<?php

declare(strict_types=1);

namespace MkConn\Sfc\Strategies\Copy;

use Illuminate\Support\Collection;
use MkConn\Sfc\Models\CopyFile;
use MkConn\Sfc\Strategies\CopyStrategy;
use Symfony\Component\Finder\Finder;

class AlphaNumericStrategy extends CopyStrategy {
    public function collectFiles(Finder $files, string $targetPath, Collection $copyFiles, ?CopyStrategyInterface $nextStrategy = null): void {
        foreach ($files as $file) {
            $copyFiles->add(new CopyFile(
                $file->getRealPath(),
                $targetPath,
                $file->getFilename()
            ));
        }

        $nextStrategy?->collectFiles($files, $targetPath, $copyFiles, $nextStrategy);
    }
}
