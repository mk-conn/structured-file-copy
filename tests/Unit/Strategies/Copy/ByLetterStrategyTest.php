<?php

declare(strict_types=1);

namespace Tests\Unit\Strategies\Copy;

use ArrayIterator;
use MkConn\Sfc\Models\CopyFile;
use MkConn\Sfc\Strategies\Copy\ByLetterStrategy;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

final class ByLetterStrategyTest extends TestCase {
    /**
     * @throws Exception
     */
    public function testCollectFilesByOneLetter(): void {
        $byLetterStrategy = (new ByLetterStrategy());

        $finderFiles = [
            $this->createConfiguredMock(SplFileInfo::class, ['getFilename' => 'abc.txt', 'getPath' => '/source', 'getMTime' => strtotime('2024-01-01'), 'getATime' => strtotime('2024-01-01')]),
            $this->createConfiguredMock(SplFileInfo::class, ['getFilename' => 'ade.txt', 'getPath' => '/source', 'getMTime' => strtotime('2024-01-02'), 'getATime' => strtotime('2024-01-02')]),
            $this->createConfiguredMock(SplFileInfo::class, ['getFilename' => 'fgh.txt', 'getPath' => '/source', 'getMTime' => strtotime('2024-01-03'), 'getATime' => strtotime('2024-01-03')]),
        ];

        $files = $this->createMock(Finder::class);

        $files->method('files')->willReturnSelf();
        $files->method('getIterator')->willReturn(new ArrayIterator($finderFiles));

        $copyFiles = collect();
        $byLetterStrategy->collectFiles($files, '/target', $copyFiles);

        self::assertCount(3, $copyFiles);

        self::assertTrue($copyFiles->contains(
            fn ($copyFile) => '/source/abc.txt' === $copyFile->fullSource() && $copyFile->fullTarget() === DS . 'target' . DS . 'a' . DS . 'abc.txt'),
            'CopyFiles does not contain expected file: abc.txt'
        );
        self::assertTrue($copyFiles->contains(
            fn ($copyFile) => '/source/ade.txt' === $copyFile->fullSource() && $copyFile->fullTarget() === DS . 'target' . DS . 'a' . DS . 'ade.txt'),
            'CopyFiles does not contain expected file: ade.txt'
        );
        self::assertTrue($copyFiles->contains(
            fn ($copyFile) => '/source/fgh.txt' === $copyFile->fullSource() && $copyFile->fullTarget() === DS . 'target' . DS . 'f' . DS . 'fgh.txt'),
            'CopyFiles does not contain expected file: fgh.txt'
        );
    }

    /**
     * @throws Exception
     */
    public function testCollectFilesByThreeLetter(): void {
        $byLetterStrategy = (new ByLetterStrategy())->withOptions([ByLetterStrategy::OPTION_BY_LETTER => 3]);

        $finderFiles = [
            $this->createConfiguredMock(SplFileInfo::class, ['getFilename' => 'abcde.txt', 'getPath' => '/source', 'getMTime' => strtotime('2024-01-01'), 'getATime' => strtotime('2024-01-01')]),
            $this->createConfiguredMock(SplFileInfo::class, ['getFilename' => 'abcxy.txt', 'getPath' => '/source', 'getMTime' => strtotime('2024-01-02'), 'getATime' => strtotime('2024-01-02')]),
            $this->createConfiguredMock(SplFileInfo::class, ['getFilename' => 'fghxy.txt', 'getPath' => '/source', 'getMTime' => strtotime('2024-01-03'), 'getATime' => strtotime('2024-01-03')]),
            $this->createConfiguredMock(SplFileInfo::class, ['getFilename' => 'xy.txt', 'getPath' => '/source', 'getMTime' => strtotime('2024-01-03'), 'getATime' => strtotime('2024-01-03')]),
        ];

        $files = $this->createMock(Finder::class);

        $files->method('files')->willReturnSelf();
        $files->method('getIterator')->willReturn(new ArrayIterator($finderFiles));

        $copyFiles = collect();
        $byLetterStrategy->collectFiles($files, '/target', $copyFiles);

        self::assertCount(4, $copyFiles);

        self::assertTrue($copyFiles->contains(
            fn (CopyFile $copyFile) => '/source/abcde.txt' === $copyFile->fullSource() && $copyFile->fullTarget() === DS . 'target' . DS . 'abc' . DS . 'abcde.txt'),
            'CopyFiles does not contain expected file: abcxy.txt'
        );
        self::assertTrue($copyFiles->contains(
            fn (CopyFile $copyFile) => '/source/abcxy.txt' === $copyFile->fullSource() && $copyFile->fullTarget() === DS . 'target' . DS . 'abc' . DS . 'abcxy.txt'),
            'CopyFiles does not contain expected file: abcxy.txt'
        );
        self::assertTrue($copyFiles->contains(
            fn (CopyFile $copyFile) => '/source/fghxy.txt' === $copyFile->fullSource() && $copyFile->fullTarget() === DS . 'target' . DS . 'fgh' . DS . 'fghxy.txt'),
            'CopyFiles does not contain expected file: fghxy.txt'
        );
        self::assertTrue($copyFiles->contains(
            fn (CopyFile $copyFile) => '/source/xy.txt' === $copyFile->fullSource() && $copyFile->fullTarget() === DS . 'target' . DS . 'xy' . DS . 'xy.txt'),
            'CopyFiles does not contain expected file: fgh.txt'
        );
    }
}
