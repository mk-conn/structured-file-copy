<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use MkConn\Sfc\Models\CopyFile;
use MkConn\Sfc\Models\Journal;
use PHPUnit\Framework\TestCase;

class JournalTest extends TestCase {
    public function testJournal(): void {
        $sourcePath = '/source';
        $targetPath = '/target';
        $copiedFiles = ['/source/01-file.txt -> /target/01-file.txt'];
        $uncopiedFiles = ['/source/02-file.txt -> /target/02-file.txt' => 'reason'];

        $journal = new Journal($sourcePath, $targetPath);
        $journal->addCopiedFile(new CopyFile($sourcePath, $targetPath, '01-file.txt', time(), time()));
        $journal->addUncopiedFile(new CopyFile($sourcePath, $targetPath, '02-file.txt', time(), time()), 'reason');

        self::assertEquals($sourcePath, $journal->sourcePath());
        self::assertEquals($targetPath, $journal->targetPath());
        self::assertEquals($copiedFiles, $journal->copiedFiles());
        self::assertEquals($uncopiedFiles, $journal->uncopiedFiles());
        self::assertTrue($journal->hasUncopiedFiles());
    }
}
