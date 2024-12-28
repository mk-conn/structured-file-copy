<?php

declare(strict_types=1);

namespace MkConn\Sfc\Tests\Unit\Strategies\Copy;

use ArrayIterator;
use MkConn\Sfc\Models\CopyFile;
use MkConn\Sfc\Strategies\Copy\DefaultCopyStrategy;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

final class DefaultCopyStrategyTest extends TestCase {
    public function testDefaultCopyStrategy(): void {
        try {
            $strategy = new DefaultCopyStrategy();

            $finderFiles = [
                $this->createConfiguredMock(SplFileInfo::class, ['getFilename' => 'file1.txt', 'getPath' => '/source', 'getMTime' => time(), 'getATime' => time()]),
                $this->createConfiguredMock(SplFileInfo::class, ['getFilename' => 'file2.txt', 'getPath' => '/source', 'getMTime' => time(), 'getATime' => time()]),
                $this->createConfiguredMock(SplFileInfo::class, ['getFilename' => 'file3.txt', 'getPath' => '/source', 'getMTime' => time(), 'getATime' => time()]),
            ];

            $files = $this->createMock(Finder::class);
            $files->method('files')->willReturnSelf();
            $files->method('getIterator')->willReturn(new ArrayIterator($finderFiles));

            $copyFiles = collect();
            $strategy->collectFiles($files, DS . 'target', $copyFiles);

            self::assertCount(count($finderFiles), $copyFiles);

            foreach ($finderFiles as $finderFile) {
                $source = $finderFile->getPath() . DS . $finderFile->getFilename();
                $fullTarget = DS . 'target' . DS . $finderFile->getFilename();

                self::assertTrue($copyFiles->contains(
                    fn (CopyFile $file) => $file->fullSource() === $source && $file->fullTarget() === $fullTarget && '/target' === $file->target()),
                    "CopyFiles does not contain expected file: {$finderFile->getFilename()}"
                );
            }
        } catch (Exception $e) {
            self::fail($e->getMessage());
        }
    }
}
