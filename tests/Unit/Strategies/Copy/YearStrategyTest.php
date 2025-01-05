<?php

declare(strict_types=1);

namespace Tests\Unit\Strategies\Copy;

use ArrayIterator;
use MkConn\Sfc\Models\CopyFile;
use MkConn\Sfc\Strategies\Copy\DateStrategy\YearStrategy;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

final class YearStrategyTest extends TestCase {
    /**
     * @throws Exception
     */
    public function testFillBucketByYear(): void {
        $strategy = new YearStrategy();

        $finderFiles = [
            $this->createConfiguredMock(SplFileInfo::class, ['getFilename' => 'file1.txt', 'getPath' => '/source', 'getMTime' => strtotime('2021-01-01'), 'getATime' => strtotime('2021-01-01')]),
            $this->createConfiguredMock(SplFileInfo::class, ['getFilename' => 'file2.txt', 'getPath' => '/source', 'getMTime' => strtotime('2023-01-01'), 'getATime' => strtotime('2023-01-01')]),
            $this->createConfiguredMock(SplFileInfo::class, ['getFilename' => 'file3.txt', 'getPath' => '/source', 'getMTime' => strtotime('1999-01-01'), 'getATime' => strtotime('1999-01-01')]),
        ];

        $files = $this->createMock(Finder::class);
        $files->method('files')->willReturnSelf();
        $files->method('getIterator')->willReturn(new ArrayIterator($finderFiles));

        $copyFiles = collect();
        $strategy->collectFiles($files, '/target', $copyFiles);

        self::assertCount(3, $copyFiles);
        self::assertTrue($copyFiles->contains(
            fn (CopyFile $copyFile) => '/source/file1.txt' === $copyFile->fullSource() && $copyFile->fullTarget() === DS . 'target' . DS . '2021' . DS . 'file1.txt'),
            'CopyFiles does not contain expected file: file1.txt'
        );
        self::assertTrue($copyFiles->contains(
            fn (CopyFile $copyFile) => '/source/file2.txt' === $copyFile->fullSource() && $copyFile->fullTarget() === DS . 'target' . DS . '2023' . DS . 'file2.txt'),
            'CopyFiles does not contain expected file: file2.txt'
        );
        self::assertTrue($copyFiles->contains(
            fn (CopyFile $copyFile) => '/source/file3.txt' === $copyFile->fullSource() && $copyFile->fullTarget() === DS . 'target' . DS . '1999' . DS . 'file3.txt'),
            'CopyFiles does not contain expected file: file3.txt'
        );
    }
}
