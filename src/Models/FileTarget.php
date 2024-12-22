<?php

declare(strict_types=1);

namespace MkConn\Sfc\Models;

readonly class FileTarget {
    public function __construct(private string $targetPath) {}

    public function targetPath(): string {
        return $this->targetPath;
    }
}
