<?php

declare(strict_types=1);

namespace MkConn\Sfc\Models;

class CopyFile {
    public function __construct(
        private readonly string $source,
        private readonly string $target,
        private readonly string $filename,
        private readonly int $mtime,
        private readonly int $atime,
    ) {}

    public function filename(): string {
        return $this->filename;
    }

    public function source(): string {
        return $this->source;
    }

    public function target(): string {
        return $this->target;
    }

    public function mtime(): int {
        return $this->mtime;
    }

    public function atime(): int {
        return $this->atime;
    }

    public function fullTarget(): string {
        return $this->target . DS . $this->filename;
    }

    public function fullSource(): string {
        return $this->source . DS . $this->filename;
    }
}
