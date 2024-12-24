<?php

declare(strict_types=1);

namespace MkConn\Sfc\Strategies\Copy;

use SplFileInfo;

class FileTypeStrategy extends GroupStrategy {
    public function getGroupKey(SplFileInfo $file): string {
        return $file->getType();
    }
}
