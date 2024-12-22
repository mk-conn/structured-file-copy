<?php

declare(strict_types=1);

namespace MkConn\Sfc\Strategies\Copy;

use Illuminate\Support\Collection;
use MkConn\Sfc\Models\CopyFile;
use MkConn\Sfc\Strategies\CopyStrategy;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

abstract class DateStrategy extends CopyStrategy {
    abstract protected function getDatePart(SplFileInfo $file): string;

    public function collectFiles(Finder $files, string $targetPath, Collection $copyFiles, ?CopyStrategyInterface $nextStrategy = null): void {
        $byDatePart = [];

        foreach ($files as $file) {
            $datePart = $this->getDatePart($file);
            $byDatePart[$datePart][] = $file;
        }

        if (!$nextStrategy) {
            foreach ($byDatePart as $datePart => $files) {
                foreach ($files as $file) {
                    $copyFiles->add(new CopyFile(
                        $file->getRealPath(),
                        $targetPath . DS . $datePart,
                        $file->getFilename()
                    ));
                }
            }

            return;
        }

        foreach ($byDatePart as $datePart => $datePartFiles) {
            $nextFinder = new Finder();
            $nextFinder->append($datePartFiles);
            $nextStrategy->collectFiles($nextFinder, $targetPath . DS . $datePart, $copyFiles, $nextStrategy);
        }
    }
}
