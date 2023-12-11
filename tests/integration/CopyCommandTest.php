<?php
declare(strict_types=1);

namespace MkConn\Sfc\Tests\integration;

use MkConn\Sfc\Commands\CopyCommand;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class CopyCommandTest extends TestCase
{

    private \org\bovigo\vfs\vfsStreamDirectory $root;

    protected function setUp(): void
    {
        $this->root = vfsStream::setup();
    }

    public function testCopyCommand()
    {
        $this->root->addChild(new vfsStreamDirectory('sourceFolder'));
        $url = $this->root->url();
        $copyCommand = new CopyCommand();
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
