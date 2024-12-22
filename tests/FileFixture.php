<?php

declare(strict_types=1);

namespace MkConn\Sfc\Tests;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

class FileFixture {
    /**
     * @param array<int, mixed> $files
     */
    public static function createFilesWithAttributes(vfsStreamDirectory $root, array $files): void {
        foreach ($files as $file) {
            $path = $root->url() . '/' . $file['name'];
            $dir = dirname($path);

            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }

            $vfsFile = vfsStream::newFile($file['name'])
                                ->at($root)
                                ->withContent($file['content'] ?? '');

            if (isset($file['creationDate'])) {
                $vfsFile->lastModified(strtotime($file['creationDate']));
            }
        }
    }
}
