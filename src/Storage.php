<?php
declare(strict_types=1);

namespace MkConn\Sfc;

use Exception;
use SplFileInfo;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class Storage {

    final public const DATE_YEAR = 'date:month';

    final public const DATE_MONTH = 'date:month';

    final public const DATE_DAY = 'date:day';

    final public const ALPHA_NAME = 'alpha:name';

    final public const NAME_LETTERS = 'name:letters';

    protected Filesystem $filesystem;
    protected string $target;
    protected array $errors = [];
    protected bool $sortInYear = false;
    protected bool $sortInMonths = false;
    protected bool $sortInDays = false;

    protected bool $sortNamed = false;

    protected int $countNameLetters = 1;
    protected int $totalFiles = 0;
    protected int $totalFileSize = 0;
    protected array $copiedFiles = [];
    protected ProgressBar $progressBar;
    protected int $filesToCopy = 0;

    public function __construct(protected array $files, array $options, protected OutputInterface $output) {
        $this->filesToCopy = count($this->files);
        $this->filesystem = new Filesystem();

        $this->setOptions($options);
        $this->prepareFiles();
    }

    public function copy(): bool {
        $this->progressBar = new ProgressBar($this->output, $this->filesToCopy);
        $this->progressBar->start();

        try {
            $targetFolder = $this->target;

            foreach ($this->files as $year => $filesPerYear) {
                if ($this->sortInYear && !$this->filesystem->exists($this->target . '/' . $year)) {
                    $this->filesystem->mkdir($targetFolder);
                }

                $targetFolder = $this->target . '/' . $year;

                if ($this->sortInMonths) {
                    foreach ($filesPerYear as $month => $filesPerMonth) {
                        $subTargetFolder = $targetFolder . '/' . $month;

                        if (!$this->filesystem->exists($subTargetFolder)) {
                            $this->filesystem->mkdir($subTargetFolder);
                        }

                        foreach ($filesPerMonth as $filePerMonthName => $file) {
                            $this->copyFile($filePerMonthName, $subTargetFolder, $file);
                        }
                    }
                } else {
                    foreach ($filesPerYear as $filePerYearName => $file) {
                        $this->copyFile($filePerYearName, $targetFolder, $file);
                    }
                }
            }

            $this->progressBar->finish();

            return true;
        } catch (Exception) {
            return false;
        }
    }

    public function hasErrors(): bool {
        return $this->errors !== [];
    }

    public function getErrors(): array {
        return $this->errors;
    }


    public function getTotalFiles(): int {
        return $this->totalFiles;
    }

    public function getTotalFileSize(): int {
        return $this->totalFileSize;
    }

    public function getCopiedFiles(): array {
        return $this->copiedFiles;
    }

    protected function copyFile(string $from, string $to, SplFileInfo $file, bool $preserveTime = true): void {
        $this->copiedFiles[$from] = [
            'to'     => $to . '/' . $file->getFilename(),
            'result' => null
        ];

        try {
            if ($this->filesystem->exists($to . '/' . $file->getFilename())) {
                $this->copiedFiles[$from]['result'] = '<error>Not copied because it already exists</error>';

                return;
            }
            $this->filesystem->copy($from, $to . '/' . $file->getFilename());

            if ($preserveTime) {
                $this->filesystem->touch(
                    $to . '/' . $file->getFilename(),
                    $file->getMTime(),
                    $file->getATime()
                );
            }
            $this->totalFileSize += $file->getSize();
            $this->totalFiles++;
            $this->copiedFiles[$from]['result'] = "<comment>Copied {$to}/{$file->getFilename()}</comment>";
        } catch (Exception $e) {
            $this->errors[$file->getFilename()] = $e->getMessage();
        }
        $this->progressBar->advance();
    }

    protected function setOptions(array $options): void {
        $this->target = $options['target'];

        if (in_array(self::DATE_YEAR, $options['sort'])) {
            $this->sortInYear = true;
        }

        if (in_array(self::DATE_MONTH, $options['sort'])) {
            $this->sortInMonths = true;
        }

        if (in_array(self::DATE_DAY, $options['sort'])) {
            $this->sortInDays = true;
        }

        if (in_array(self::ALPHA_NAME, $options['sort'])) {
            $this->sortNamed = true;

            if (isset($options[self::NAME_LETTERS])) {
                $this->countNameLetters = $options['sort'][self::NAME_LETTERS];
            }
        }
    }

    protected function byYear($files): array {
        $return = [];

        foreach ($files as $fileName => $file) {
            $return[date('Y', $file->getMTime())][$fileName] = $file;
        }

        return $return;
    }

    protected function byMonth(array $files): array {
        $return = [];

        foreach ($files as $fileName => $file) {
            $return[date('m', $file->getMTime())][$fileName] = $file;
        }

        return $return;
    }

    protected function byDay($files) {

    }

    protected function byName($files, $countLetters = 1) {

    }

    protected function prepareFiles(): void {
        $files = [];

        if ($this->sortInYear) {
            $files = $this->byYear($this->files);
        }

        if ($this->sortInMonths) {
            if ($this->sortInYear) {
                foreach ($files as $year => $filesPerYear) {
                    $files[$year] = $this->byMonth($filesPerYear);
                }
            } else {
                $files = $this->byMonth($files);
            }
        }

        if ($files !== []) {
            $this->files = $files;
        }

    }
}
