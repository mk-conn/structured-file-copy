<?php

declare(strict_types=1);

namespace MkConn\Sfc\Factories;

use MkConn\Sfc\Models\Journal;

class JournalFactory {
    public function create(string $sourcePath, string $targetPath): Journal {
        return new Journal($sourcePath, $targetPath);
    }
}
