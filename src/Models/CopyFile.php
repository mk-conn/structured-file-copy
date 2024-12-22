<?php

declare(strict_types=1);

namespace MkConn\Sfc\Models;

class CopyFile {
    public function __construct(
        private readonly string $source,
        private string $target,
        private readonly string $filename,
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

    public function addToTarget(string $path): void {
        $this->target = $this->target . DIRECTORY_SEPARATOR . $path;
    }

    public function fullTarget(): string {
        return $this->target . DIRECTORY_SEPARATOR . $this->filename;
    }
}
