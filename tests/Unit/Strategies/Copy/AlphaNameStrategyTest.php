<?php

declare(strict_types=1);

namespace MkConn\Sfc\Tests\Unit\Strategies\Copy;

use ArrayIterator;
use MkConn\Sfc\Models\CopyFile;
use MkConn\Sfc\Strategies\Copy\AlphaNumericStrategy;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

class AlphaNameStrategyTest extends TestCase {
    public function testFillBucket(): void {
        try {
            $strategy = new AlphaNumericStrategy();

            $finderFiles = [
                $this->createConfiguredMock(SplFileInfo::class, ['getFilename' => 'file1.txt', 'getRealPath' => '/source/file1.txt', 'getPath' => '/source', 'getMTime' => time()]),
                $this->createConfiguredMock(SplFileInfo::class, ['getFilename' => 'file2.txt', 'getRealPath' => '/source/file2.txt', 'getPath' => '/source', 'getMTime' => time()]),
                $this->createConfiguredMock(SplFileInfo::class, ['getFilename' => 'file3.txt', 'getRealPath' => '/source/file3.txt', 'getPath' => '/source', 'getMTime' => time()]),
            ];

            $files = $this->createMock(Finder::class);
            $files->method('files')->willReturnSelf();
            $files->method('getIterator')->willReturn(new ArrayIterator($finderFiles));

            $copyFiles = collect();
            $strategy->collectFiles($files, DS . 'target', $copyFiles);

            self::assertCount(count($finderFiles), $copyFiles);

            foreach ($finderFiles as $finderFile) {
                $source = $finderFile->getRealPath();
                $fullTarget = DS . 'target' . DS . $finderFile->getFilename();

                self::assertTrue($copyFiles->contains(
                    fn (CopyFile $file) => $file->source() === $source && $file->fullTarget() === $fullTarget && '/target' === $file->target()),
                    "CopyFiles does not contain expected file: {$finderFile->getFilename()}"
                );
            }
        } catch (Exception $e) {
            self::fail($e->getMessage());
        }
    }
}
