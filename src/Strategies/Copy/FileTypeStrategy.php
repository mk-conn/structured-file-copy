<?php

declare(strict_types=1);

namespace MkConn\Sfc\Strategies\Copy;

use MkConn\Sfc\Services\FileService\FileTypes;
use Symfony\Component\Finder\SplFileInfo;

class FileTypeStrategy extends GroupStrategy {
    public function __construct(private readonly FileTypes $fileTypes) {}

    public function getGroupKey(SplFileInfo $file): string {
        return $this->fileTypes->getTypeForFile($file);
    }

    public function withOptions(array $options): static {
        return $this;
    }
}
