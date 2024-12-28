<?php

declare(strict_types=1);

namespace Tests\Integration;

use MkConn\Sfc\Enums\FilterType;
use MkConn\Sfc\Exceptions\FileCopyException;
use MkConn\Sfc\Factories\CopyOptionsFactory;
use MkConn\Sfc\Models\CopyOptions;
use MkConn\Sfc\Models\Filter;
use MkConn\Sfc\Services\CopyService;
use MkConn\Sfc\Strategies\Copy\ByLetterStrategy;
use MkConn\Sfc\Strategies\Copy\DateStrategy\DayStrategy;
use MkConn\Sfc\Strategies\Copy\DateStrategy\MonthStrategy;
use MkConn\Sfc\Strategies\Copy\DateStrategy\YearStrategy;
use MkConn\Sfc\Strategies\Copy\DefaultCopyStrategy;
use MkConn\Sfc\Strategies\Copy\FileTypeStrategy;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Finder\Finder;
use Tests\FileFixture;
use Tests\SfcTestCase;

final class CopyServiceTest extends SfcTestCase {
    /**
     * @throws FileCopyException
     */
    public function testCopyWithDefaultStrategy(): void {
        [$source, $target] = $this->setupTestEnvironment();

        $files = ['01_file.txt', 'FILE_a.txt', 'file_B.txt', 'file_b.txt', 'z_ile_a_copy.txt', 'audio1.mp3', 'X_audio1.wav', '01_movie.mov'];

        $options = new CopyOptions($source, $target, [new DefaultCopyStrategy()]);
        $this->copyService()->copy($options, new NullOutput());

        $this->assertDirectoryStructure($target, $files);
    }

    /**
     * @throws FileCopyException
     */
    public function testCopySortedByYear(): void {
        [$source, $target] = $this->setupTestEnvironment();
        $options = new CopyOptions($source, $target, [new YearStrategy()]);
        $this->copyService()->copy($options, new NullOutput());

        $finder = new Finder();
        $finder->in($target);

        $directories = $finder->directories();
        $expectedDirectories = [
            '2021' => ['01_movie.mov'],
            '2022' => ['FILE_a.txt', 'file_B.txt'],
            '2024' => ['01_file.txt', 'file_b.txt', 'z_ile_a_copy.txt', 'audio1.mp3', 'X_audio1.wav'],
        ];
        $exptetedDirNames = array_keys($expectedDirectories);

        foreach ($directories as $directory => $dirSplInfo) {
            self::assertTrue(in_array($dirSplInfo->getFilename(), $exptetedDirNames));
            $directoryFinder = new Finder();
            $directoryFinder->in($directory);

            $files = $directoryFinder->files();

            foreach ($files as $file) {
                self::assertTrue(in_array($file->getFilename(), $expectedDirectories[$dirSplInfo->getFilename()]), "File {$file->getFilename()} is not in the correct directory");
            }
        }
    }

    public function testtestCopySortedByYearMonthFileTypeAndLetter(): void {
        [$source, $target] = $this->setupTestEnvironment();

        $options = new CopyOptions($source, $target, [new YearStrategy(), new MonthStrategy(), $this->fromContainer(FileTypeStrategy::class), (new ByLetterStrategy())->withOptions([ByLetterStrategy::OPTION_BY_LETTER => 2])]);
        $this->copyService()->copy($options, new NullOutput());

        $expectedStructure = [
            '2021' => [
                '12' => [
                    'video' => [
                        '01' => ['01_movie.mov'],
                    ],
                ],
            ],
            '2022' => [
                '09' => [
                    'text' => [
                        'FI' => ['FILE_a.txt'],
                    ],
                ],
                '12' => [
                    'text' => [
                        'fi' => ['file_B.txt'],
                    ],
                ],
            ],
            '2024' => [
                '01' => [
                    'text'  => [
                        '01' => ['01_file.txt'],
                    ],
                ],
                '10' => [
                    'text'  => [
                        'fi' => ['file_b.txt'],
                    ],
                ],
                '12' => [
                    'text'  => [
                        'z_' => ['z_ile_a_copy.txt'],
                    ],
                    'audio' => [
                        'au' => ['audio1.mp3'],
                        'X_' => ['X_audio1.wav'],
                    ],
                ],
            ],
        ];

        $this->assertDirectoryStructure($target, $expectedStructure);
    }

    public function testCopySortedByMonth(): void {
        [$source, $target] = $this->setupTestEnvironment();

        $options = new CopyOptions($source, $target, [new MonthStrategy()]);
        $this->copyService()->copy($options, new NullOutput());

        $expectedStructure = [
            '01' => ['01_file.txt'],
            '09' => ['FILE_a.txt'],
            '10' => ['file_b.txt'],
            '12' => ['file_B.txt', 'z_ile_a_copy.txt', 'audio1.mp3', 'X_audio1.wav', '01_movie.mov'],
        ];

        $this->assertDirectoryStructure($target, $expectedStructure);
    }

    public function testCopySortedByDay(): void {
        [$source, $target] = $this->setupTestEnvironment();

        $options = new CopyOptions($source, $target, [new DayStrategy()]);
        $journal = $this->copyService()->copy($options, new NullOutput());

        $expectedStructure = [
            '01' => ['01_file.txt', 'FILE_a.txt', 'z_ile_a_copy.txt', 'audio1.mp3', 'X_audio1.wav'],
            '02' => ['file_B.txt'],
            '03' => ['01_movie.mov'],
        ];

        $this->assertDirectoryStructure($target, $expectedStructure);
    }

    /**
     * @throws FileCopyException
     */
    public function testCopySortedByYearAndMonth(): void {
        [$source, $target] = $this->setupTestEnvironment();

        $options = new CopyOptions($source, $target, [new YearStrategy(), new MonthStrategy()]);
        $this->copyService()->copy($options, new NullOutput());

        $expectedStructure = [
            '2021' => [
                '12' => ['01_movie.mov'],
            ],
            '2022' => [
                '09' => ['FILE_a.txt'],
                '12' => ['file_B.txt'],
            ],
            '2024' => [
                '01' => ['01_file.txt'],
                '10' => ['file_b.txt'],
                '12' => ['z_ile_a_copy.txt', 'audio1.mp3', 'X_audio1.wav'],
            ],
        ];

        $this->assertDirectoryStructure($target, $expectedStructure);
    }

    public function testCopySortedByYearAndMonthAndDay(): void {
        [$source, $target] = $this->setupTestEnvironment();

        $options = new CopyOptions($source, $target, [new YearStrategy(), new MonthStrategy(), new DayStrategy()]);
        $this->copyService()->copy($options, new NullOutput());

        $expectedStructure = [
            '2021' => [
                '12' => [
                    '03' => ['01_movie.mov'],
                ],
            ],
            '2022' => [
                '09' => [
                    '01' => ['FILE_a.txt'],
                ],
                '12' => [
                    '02' => ['file_B.txt'],
                ],
            ],
            '2024' => [
                '01' => [
                    '01' => ['01_file.txt'],
                ],
                '10' => [
                    '01' => ['file_b.txt'],
                ],
                '12' => [
                    '01' => ['z_ile_a_copy.txt', 'audio1.mp3', 'X_audio1.wav'],
                ],
            ],
        ];

        $this->assertDirectoryStructure($target, $expectedStructure);
    }

    public function testCopyExplicitFileTypes(): void {
        [$source, $target] = $this->setupTestEnvironment();

        $options = new CopyOptions($source, $target, [$this->fromContainer(FileTypeStrategy::class)]);
        $this->copyService()->copy($options, new NullOutput());

        $expectedStructure = [
            'audio' => ['audio1.mp3', 'X_audio1.wav'],
            'text'  => ['01_file.txt', 'FILE_a.txt', 'file_B.txt', 'file_b.txt', 'z_ile_a_copy.txt'],
            'video' => ['01_movie.mov'],
        ];

        $this->assertDirectoryStructure($target, $expectedStructure);
    }

    public function testCopyWithoutSpecificFileExtensions(): void {
        [$source, $target] = $this->setupTestEnvironment();
        $copyOptionsFactory = $this->fromContainer(CopyOptionsFactory::class);
        $options = $copyOptionsFactory->create($source, $target, excluded: [new Filter(FilterType::EXT, 'txt')]);
        $this->copyService()->copy($options, new NullOutput());

        $expectedStructure = [
            'audio1.mp3',
            'X_audio1.wav',
            '01_movie.mov',
        ];

        $this->assertDirectoryStructure($target, $expectedStructure);
    }

    /**
     * @return array<array{name: string, content: string, creationDate: string}>
     */
    private function files(): array {
        return [
            [
                'name'             => '01_file.txt',
                'content'          => 'some text',
                'creationDate'     => '2024-01-01 09:00:00',
                'modificationDate' => '2024-01-01 09:00:00',
            ],
            [
                'name'             => 'FILE_a.txt',
                'content'          => 'some text',
                'creationDate'     => '2022-09-01 09:00:00',
                'modificationDate' => '2022-09-01 09:05:00',
            ],
            [
                'name'             => 'file_B.txt',
                'content'          => 'some text',
                'creationDate'     => '2022-12-02 10:00:00',
                'modificationDate' => '2022-12-02 10:05:00',
            ],
            [
                'name'             => 'file_b.txt',
                'content'          => 'some text',
                'creationDate'     => '2024-10-01 10:00:00',
                'modificationDate' => '2024-10-01 10:05:00',
            ],
            [
                'name'             => 'z_ile_a_copy.txt',
                'content'          => 'some text',
                'creationDate'     => '2024-12-01 08:05:00',
                'modificationDate' => '2024-12-01 08:10:00',
            ],
            [
                'name'             => 'audio1.mp3',
                'content'          => 'some text',
                'creationDate'     => '2024-12-01 08:05:00',
                'modificationDate' => '2024-12-01 08:10:00',
            ],
            [
                'name'             => 'X_audio1.wav',
                'content'          => 'some text',
                'creationDate'     => '2024-12-01 08:05:00',
                'modificationDate' => '2024-12-01 08:10:00',
            ],
            [
                'name'             => '01_movie.mov',
                'content'          => 'some text',
                'creationDate'     => '2021-12-03 08:05:00',
                'modificationDate' => '2021-12-03 08:10:00',
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

    /**
     * @return string[]
     */
    private function setupTestEnvironment(): array {
        $fs = $this->setupFilesystem();
        $baseDir = $fs->url();
        $source = $baseDir . '/source';
        $target = $baseDir . '/target';

        return [$source, $target];
    }

    private function copyService(): CopyService {
        return $this->fromContainer(CopyService::class);
    }

    /**
     * @param array<string|int, array<string, mixed>|array<int, string>|string> $expectedStructure
     */
    private function assertDirectoryStructure(string $baseDir, array $expectedStructure): void {
        foreach ($expectedStructure as $key => $value) {
            // Determine if the current key represents a file or a subdirectory

            if (is_array($value)) {
                $currentPath = $baseDir . DS . $key;

                // Leaf directory containing files
                if (array_is_list($value)) {
                    foreach ($value as $file) {
                        $filePath = $currentPath . DS . $file;

                        // Assert the file exists
                        self::assertFileExists($filePath, "File '$filePath' does not exist.");
                    }
                // Subdirectory case
                } else {
                    // Assert the directory exists
                    self::assertDirectoryExists($currentPath, "Directory '$currentPath' does not exist.");

                    // Recursively check the subdirectory structure
                    $this->assertDirectoryStructure($currentPath, $value);
                }
            } else {
                $filePath = $baseDir . DS . $value;
                self::assertFileExists($filePath, "File '$filePath' does not exist.");
            }
        }
    }
}
