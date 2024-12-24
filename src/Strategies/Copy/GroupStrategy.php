<?php

declare(strict_types=1);

namespace MkConn\Sfc\Strategies\Copy;

use Illuminate\Support\Collection;
use MkConn\Sfc\Models\CopyFile;
use MkConn\Sfc\Strategies\CopyStrategy;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

abstract class GroupStrategy extends CopyStrategy {
    abstract public function getGroupKey(SplFileInfo $file): string;

    public function collectFiles(Finder $files, string $targetPath, Collection $copyFiles, ?CopyStrategyInterface $nextStrategy = null): void {
        $groupedFiles = [];

        foreach ($files as $file) {
            $groupKey = $this->getGroupKey($file);
            $groupedFiles[$groupKey][] = $file;
        }

        if (!$nextStrategy) {
            foreach ($groupedFiles as $groupKey => $files) {
                foreach ($files as $file) {
                    $copyFiles->add(new CopyFile(
                        $file->getRealPath(),
                        $targetPath . DS . $groupKey,
                        $file->getFilename()
                    ));
                }
            }

            return;
        }

        foreach ($groupedFiles as $groupKey => $groupFiles) {
            $nextFinder = new Finder();
            $nextFinder->append($groupFiles);
            $nextStrategy->collectFiles($nextFinder, $targetPath . DS . $groupKey, $copyFiles);
        }
    }
}
