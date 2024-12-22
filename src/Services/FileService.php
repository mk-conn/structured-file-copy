<?php

declare(strict_types=1);

namespace MkConn\Sfc\Services;

use Symfony\Component\Filesystem\Filesystem;

class FileService {
    public function __construct(private readonly Filesystem $filesystem) {}

    public function copy(string $source, string $target): void {}
}
