<?php

declare(strict_types=1);

namespace Tests\Integration;

use MkConn\Sfc\Commands\CopyCommand;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\SfcTestCase;

final class CopyCommandTest extends SfcTestCase {
    private vfsStreamDirectory $root;

    protected function setUp(): void {
        $this->root = vfsStream::setup();
    }

    public function testCopyCommand(): void {
        $this->root->addChild(new vfsStreamDirectory('sourceFolder'));
        $url = $this->root->url();
        $copyCommand = $this->container()->get(CopyCommand::class);

        $tester = new CommandTester($copyCommand);
        $tester->execute(
            [
                '--source'     => $this->root->getChild('sourceFolder')->url(),
                '--target'     => $url . '/targetFolder',
                '--include'    => 'ext:txt,ext:md,size:>=100,type:video',
                '--exclude'    => 'name:file1',
                '--sort'       => 'by:date:year,by:letter',
                '--by-letter'  => '3',
            ]
        );

        $tester->assertCommandIsSuccessful('Copy command was not successful.');
    }
}
