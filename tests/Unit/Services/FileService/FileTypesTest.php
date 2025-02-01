<?php

declare(strict_types=1);

namespace Tests\Unit\Services\FileService;

use MkConn\Sfc\Services\FileService\FileTypes;
use Tests\SfcTestCase;

class FileTypesTest extends SfcTestCase {
    public function testFileTypeToExtensions(): void {
        $fileTypes = $this->fromContainer(FileTypes::class);

        $extensions = $fileTypes->getFilesForType('pdf');
        self::assertSame(['pdf'], $extensions);
    }
}
