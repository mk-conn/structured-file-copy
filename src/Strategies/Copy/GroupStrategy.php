<?php

declare(strict_types=1);

namespace MkConn\Sfc\Strategies\Copy;

use Illuminate\Support\Collection;
use MkConn\Sfc\Models\CopyFile;
use MkConn\Sfc\Strategies\CopyStrategy;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

abstract class GroupStrategy extends CopyStrategy {
    abstract public function getGroupKey(SplFileInfo $file): string;

    public function collectFiles(Finder $files, string $targetPath, Collection $copyFiles): void {
        $groupedFiles = [];

        foreach ($files as $file) {
            $groupKey = $this->getGroupKey($file);
            $groupedFiles[$groupKey][] = $file;
        }

        if (!$this->nextStrategy) {
            foreach ($groupedFiles as $groupKey => $files) {
                foreach ($files as $file) {
                    if ($file->isDir()) {
                        continue;
                    }
                    $copyFiles->add(new CopyFile(
                        $file->getPath(),
                        $targetPath . DS . $groupKey,
                        $file->getFilename(),
                        $file->getMTime(),
                        $file->getATime(),
                    ));
                }
            }

            return;
        }

        foreach ($groupedFiles as $groupKey => $groupFiles) {
            $nextFinder = new Finder();
            $nextFinder->append($groupFiles);
            $this->nextStrategy->collectFiles($nextFinder, $targetPath . DS . $groupKey, $copyFiles);
        }
    }
}
