<?php

declare(strict_types=1);

namespace MkConn\Sfc\Tests\Unit;

use Exception;
use MkConn\Sfc\Models\Options;
use MkConn\Sfc\Models\Sort;
use MkConn\Sfc\Services\CopyService;
use MkConn\Sfc\Tests\FileFixture;
use MkConn\Sfc\Tests\SfcTestCase;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use Symfony\Component\Finder\Finder;

final class CopyServiceTest extends SfcTestCase {
    public function testCopySortedByName(): void {
        $fs = $this->setupFilesystem();
        $baseDir = $fs->url();
        $source = $baseDir . '/source';
        $target = $baseDir . '/target';

        $files = ['01_file.txt', 'FILE_a.txt', 'file_B.txt', 'file_b.txt', 'z_ile_a_copy.txt', 'audio1.mp3', 'X_audio1.wav', '01_movie.mov'];

        try {
            $options = new Options($source, $target, Sort::create());
            $copyService = $this->container()->get(CopyService::class);
            $copyService->copy($options);

            $finder = new Finder();
            $finder->in($target);

            $foundFiles = $finder->files();
            $foundFiles = array_map(fn ($file) => $file->getFilename(), iterator_to_array($foundFiles));

            self::assertEquals($files, $foundFiles);
        } catch (Exception $e) {
            self::fail($e->getMessage());
        }
    }

    public function testCopySortedByYear(): void {}

    public function testCopySortedByMonth(): void {}

    public function testCopySortedByDay(): void {}

    public function testCopySortedByYearAndMonth(): void {}

    public function testCopySortedByYearAndMonthAndDay(): void {}

    public function testCopyExplicitFileTypes(): void {}

    public function testCopyWithoutSpecificFileExtensions(): void {}

    /**
     * @return array<array{name: string, content: string, creationDate: int}>
     */
    private function files(): array {
        return [
            [
                'name'         => '01_file.txt',
                'content'      => 'some text',
                'creationDate' => '2024-01-01 09:00:00',
            ],
            [
                'name'         => 'FILE_a.txt',
                'content'      => 'some text',
                'creationDate' => '2022-09-01 09:00:00',
            ],
            [
                'name'         => 'file_B.txt',
                'content'      => 'some text',
                'creationDate' => '2024-12-01 10:00:00',
            ],
            [
                'name'         => 'file_b.txt',
                'content'      => 'some text',
                'creationDate' => '2024-10-01 10:00:00',
            ],
            [
                'name'         => 'z_ile_a_copy.txt',
                'content'      => 'some text',
                'creationDate' => '2024-12-01 08:05:00',
            ],
            [
                'name'         => 'audio1.mp3',
                'content'      => 'some text',
                'creationDate' => '2024-12-01 08:05:00',
            ],
            [
                'name'         => 'X_audio1.wav',
                'content'      => 'some text',
                'creationDate' => '2024-12-01 08:05:00',
            ],
            [
                'name'         => '01_movie.mov',
                'content'      => 'some text',
                'creationDate' => '2024-12-01 08:05:00',
            ],
        ];
    }

    private function setupFilesystem(): vfsStreamDirectory {
        $fs = vfsStream::setup();
        $sourceDir = new vfsStreamDirectory('source');
        $fs->addChild($sourceDir);

        FileFixture::createFilesWithAttributes($sourceDir, $this->files());

        return $fs;
    }
}
