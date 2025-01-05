<?php

declare(strict_types=1);

namespace Tests\Integration;

use MkConn\Sfc\Commands\CopyCommand;
use MkConn\Sfc\Services\FilesystemService;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\MockObject\Exception;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\SfcTestCase;

final class CopyCommandTest extends SfcTestCase {
    /**
     * @throws Exception
     */
    public function testCopyCommand(): void {
        $filesystemService = $this->createMock(FilesystemService::class);
        $filesystemService->method('realpath')->willReturnCallback(fn ($path) => $path);
        $filesystemService->method('fileExists')->willReturnCallback(fn ($path) => true);

        $this->container()->set(FilesystemService::class, $filesystemService);

        $fs = vfsStream::setup();
        $fs->addChild(new vfsStreamDirectory('source'));

        $source = $fs->url() . '/source';
        $target = $fs->url() . '/target';

        $copyCommand = $this->fromContainer(CopyCommand::class);

        $tester = new CommandTester($copyCommand);
        $tester->execute(
            [
                '--source'     => $source,
                '--target'     => $target,
                '--include'    => 'ext:txt,ext:md,size:>=100,type:video',
                '--exclude'    => 'name:file1',
                '--sort'       => 'by:date:year,by:letter',
                '--by-letter'  => '3',
            ]
        );

        $tester->assertCommandIsSuccessful('Copy command was not successful.');
    }
}
