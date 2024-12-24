<?php

declare(strict_types=1);

namespace MkConn\Sfc\Tests\Unit\Strategies\Copy;

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
            $this->createConfiguredMock(SplFileInfo::class, ['getFilename' => 'abc.txt', 'getRealPath' => '/source/abc.txt', 'getMTime' => strtotime('2024-01-01')]),
            $this->createConfiguredMock(SplFileInfo::class, ['getFilename' => 'ade.txt', 'getRealPath' => '/source/ade.txt', 'getMTime' => strtotime('2024-01-02')]),
            $this->createConfiguredMock(SplFileInfo::class, ['getFilename' => 'fgh.txt', 'getRealPath' => '/source/fgh.txt', 'getMTime' => strtotime('2024-01-03')]),
        ];

        $files = $this->createMock(Finder::class);

        $files->method('files')->willReturnSelf();
        $files->method('getIterator')->willReturn(new ArrayIterator($finderFiles));

        $copyFiles = collect();
        $byLetterStrategy->collectFiles($files, '/target', $copyFiles);

        self::assertCount(3, $copyFiles);

        self::assertTrue($copyFiles->contains(
            fn ($copyFile) => '/source/abc.txt' === $copyFile->source() && $copyFile->fullTarget() === DS . 'target' . DS . 'a' . DS . 'abc.txt'),
            'CopyFiles does not contain expected file: abc.txt'
        );
        self::assertTrue($copyFiles->contains(
            fn ($copyFile) => '/source/ade.txt' === $copyFile->source() && $copyFile->fullTarget() === DS . 'target' . DS . 'a' . DS . 'ade.txt'),
            'CopyFiles does not contain expected file: ade.txt'
        );
        self::assertTrue($copyFiles->contains(
            fn ($copyFile) => '/source/fgh.txt' === $copyFile->source() && $copyFile->fullTarget() === DS . 'target' . DS . 'f' . DS . 'fgh.txt'),
            'CopyFiles does not contain expected file: fgh.txt'
        );
    }

    /**
     * @throws Exception
     */
    public function testCollectFilesByThreeLetter(): void {
        $byLetterStrategy = (new ByLetterStrategy())->withPrefixLength(3);

        $finderFiles = [
            $this->createConfiguredMock(SplFileInfo::class, ['getFilename' => 'abcde.txt', 'getRealPath' => '/source/abcde.txt', 'getMTime' => strtotime('2024-01-01')]),
            $this->createConfiguredMock(SplFileInfo::class, ['getFilename' => 'abcxy.txt', 'getRealPath' => '/source/abcxy.txt', 'getMTime' => strtotime('2024-01-02')]),
            $this->createConfiguredMock(SplFileInfo::class, ['getFilename' => 'fghxy.txt', 'getRealPath' => '/source/fghxy.txt', 'getMTime' => strtotime('2024-01-03')]),
            $this->createConfiguredMock(SplFileInfo::class, ['getFilename' => 'xy.txt', 'getRealPath' => '/source/xy.txt', 'getMTime' => strtotime('2024-01-03')]),
        ];

        $files = $this->createMock(Finder::class);

        $files->method('files')->willReturnSelf();
        $files->method('getIterator')->willReturn(new ArrayIterator($finderFiles));

        $copyFiles = collect();
        $byLetterStrategy->collectFiles($files, '/target', $copyFiles);

        self::assertCount(4, $copyFiles);

        self::assertTrue($copyFiles->contains(
            fn (CopyFile $copyFile) => '/source/abcde.txt' === $copyFile->source() && $copyFile->fullTarget() === DS . 'target' . DS . 'abc' . DS . 'abcde.txt'),
            'CopyFiles does not contain expected file: abcxy.txt'
        );
        self::assertTrue($copyFiles->contains(
            fn (CopyFile $copyFile) => '/source/abcxy.txt' === $copyFile->source() && $copyFile->fullTarget() === DS . 'target' . DS . 'abc' . DS . 'abcxy.txt'),
            'CopyFiles does not contain expected file: abcxy.txt'
        );
        self::assertTrue($copyFiles->contains(
            fn (CopyFile $copyFile) => '/source/fghxy.txt' === $copyFile->source() && $copyFile->fullTarget() === DS . 'target' . DS . 'fgh' . DS . 'fghxy.txt'),
            'CopyFiles does not contain expected file: fghxy.txt'
        );
        self::assertTrue($copyFiles->contains(
            fn (CopyFile $copyFile) => '/source/xy.txt' === $copyFile->source() && $copyFile->fullTarget() === DS . 'target' . DS . 'xy' . DS . 'xy.txt'),
            'CopyFiles does not contain expected file: fgh.txt'
        );
    }
}
