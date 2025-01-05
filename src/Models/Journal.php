<?php

declare(strict_types=1);

namespace MkConn\Sfc\Models;

class Journal {
    /**
     * @param array<string>         $copiedFiles
     * @param array<string, string> $uncopiedFiles
     */
    public function __construct(
        private readonly string $sourcePath,
        private readonly string $targetPath,
        private array $copiedFiles = [],
        private array $uncopiedFiles = []
    ) {}

    public function addCopiedFile(CopyFile $copyFile): void {
        $this->copiedFiles[] = $copyFile->fullSource() . ' -> ' . $copyFile->fullTarget();
    }

    public function addUncopiedFile(CopyFile $copyFile, string $reason): void {
        $this->uncopiedFiles[$copyFile->fullSource() . ' -> ' . $copyFile->fullTarget()] = $reason;
    }

    public function sourcePath(): string {
        return $this->sourcePath;
    }

    public function targetPath(): string {
        return $this->targetPath;
    }

    /**
     * @return string[]
     */
    public function copiedFiles(): array {
        return $this->copiedFiles;
    }

    /**
     * @return array <string, string>
     */
    public function uncopiedFiles(): array {
        return $this->uncopiedFiles;
    }

    public function hasUncopiedFiles(): bool {
        return count($this->uncopiedFiles) > 0;
    }
}
