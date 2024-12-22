<?php

declare(strict_types=1);

namespace MkConn\Sfc\Tests\Integration;

use MkConn\Sfc\Commands\CopyCommand;
use MkConn\Sfc\Tests\SfcTestCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use Symfony\Component\Console\Tester\CommandTester;

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
                '--source' => $this->root->getChild('sourceFolder')->url(),
                '--target' => $url . '/targetFolder',
            ]
        );

        $tester->assertCommandIsSuccessful('Copy command was not successful.');
    }
}
