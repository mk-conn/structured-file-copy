<?php

declare(strict_types=1);

namespace MkConn\Sfc\Strategies\Copy;

use Illuminate\Support\Collection;
use MkConn\Sfc\Models\CopyFile;
use MkConn\Sfc\Strategies\CopyStrategy;
use Symfony\Component\Finder\Finder;

class DefaultCopyStrategy extends CopyStrategy {
    public function collectFiles(Finder $files, string $targetPath, Collection $copyFiles): void {
        foreach ($files as $file) {
            if ($file->isDir()) {
                continue;
            }
            $copyFiles->add(new CopyFile(
                $file->getPath(),
                $targetPath,
                $file->getFilename(),
                $file->getMTime(),
                $file->getATime(),
            ));
        }

        $this->nextStrategy?->collectFiles($files, $targetPath, $copyFiles);
    }
}
