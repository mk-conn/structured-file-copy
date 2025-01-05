<?php

declare(strict_types=1);

namespace MkConn\Sfc\Services;

class FilesystemService {
    public function realpath(string $path): false|string {
        return realpath($path);
    }

    public function fileExists(string $path): bool {
        return file_exists($path);
    }
}
