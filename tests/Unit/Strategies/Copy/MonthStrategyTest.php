<?php

declare(strict_types=1);

namespace MkConn\Sfc\Tests\Unit\Strategies\Copy;

use ArrayIterator;
use MkConn\Sfc\Models\CopyFile;
use MkConn\Sfc\Strategies\Copy\DateStrategy\MonthStrategy;
use PHPUnit\Framework\TestCase;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

class MonthStrategyTest extends TestCase {
    public function testMonthStrategy(): void {
        $strategy = new MonthStrategy();

        $finderFiles = [
            $this->createConfiguredMock(SplFileInfo::class, ['getFilename' => 'file1.txt', 'getRealPath' => '/source/file1.txt', 'getMTime' => strtotime('2024-01-01')]),
            $this->createConfiguredMock(SplFileInfo::class, ['getFilename' => 'file2.txt', 'getRealPath' => '/source/file2.txt', 'getMTime' => strtotime('2024-02-01')]),
            $this->createConfiguredMock(SplFileInfo::class, ['getFilename' => 'file3.txt', 'getRealPath' => '/source/file3.txt', 'getMTime' => strtotime('2024-03-01')]),
        ];

        $files = $this->createMock(Finder::class);
        $files->method('files')->willReturnSelf();
        $files->method('getIterator')->willReturn(new ArrayIterator($finderFiles));

        $copyFiles = collect();
        $strategy->collectFiles($files, DS . 'target', $copyFiles);

        self::assertCount(3, $copyFiles);
        self::assertTrue($copyFiles->contains(
            fn (CopyFile $copyFile) => '/source/file1.txt' === $copyFile->source() && $copyFile->fullTarget() === DS . 'target' . DS . '01' . DS . 'file1.txt'),
            'CopyFiles does not contain expected file: file1.txt'
        );
        self::assertTrue($copyFiles->contains(
            fn (CopyFile $copyFile) => '/source/file2.txt' === $copyFile->source() && $copyFile->fullTarget() === DS . 'target' . DS . '02' . DS . 'file2.txt'),
            'CopyFiles does not contain expected file: file2.txt'
        );
        self::assertTrue($copyFiles->contains(
            fn (CopyFile $copyFile) => '/source/file3.txt' === $copyFile->source() && $copyFile->fullTarget() === DS . 'target' . DS . '03' . DS . 'file3.txt'),
            'CopyFiles does not contain expected file: file3.txt'
        );
    }
}
