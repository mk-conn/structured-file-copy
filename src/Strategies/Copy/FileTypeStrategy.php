<?php

declare(strict_types=1);

namespace MkConn\Sfc\Strategies\Copy;

use Symfony\Component\Finder\Finder;

class FileTypeStrategy implements CopyStrategyInterface {
    public function collectFiles(Finder $files, string $sourcePath): void {}
}
