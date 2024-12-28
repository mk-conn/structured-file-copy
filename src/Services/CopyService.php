<?php

declare(strict_types=1);

namespace MkConn\Sfc\Services;

use Illuminate\Support\Collection;
use MkConn\Sfc\Exceptions\FileCopyException;
use MkConn\Sfc\Factories\FileFinderFactory;
use MkConn\Sfc\Factories\JournalFactory;
use MkConn\Sfc\Models\CopyFile;
use MkConn\Sfc\Models\CopyOptions;
use MkConn\Sfc\Models\Journal;
use MkConn\Sfc\Strategies\StrategyPipeline;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class CopyService {
    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly FileFinderFactory $fileFinderFactory,
        private readonly StrategyPipeline $strategyPipeline,
        private readonly JournalFactory $journalFactory
    ) {}

    /**
     * @throws FileCopyException
     */
    public function copy(CopyOptions $options, OutputInterface $output): Journal {
        if (!$this->filesystem->exists($options->source)) {
            throw new FileCopyException($options->source, $options->target, 'Source folder does not exist');
        }

        $copyFiles = $this->strategyPipeline->run(
            $options->strategies,
            $this->fileFinderFactory->create($options->source, $options->included, $options->excluded),
            $options->target
        );
        $journal = $this->journalFactory->create($options->source, $options->target);

        $this->copyFiles($copyFiles, $options->preserveTimestamps, $options->overwrite, $journal, $output);

        return $journal;
    }

    /**
     * @param Collection<array-key, CopyFile> $copyFiles
     */
    public function copyFiles(Collection $copyFiles, bool $preserveTimestamps, bool $overwrite, Journal $journal, OutputInterface $output): void {
        $copyFiles->each(function (CopyFile $copyFile) use ($preserveTimestamps, $overwrite, $journal, $output): void {
            if (!$overwrite && $this->filesystem->exists($copyFile->fullTarget())) {
                $journal->addUncopiedFile($copyFile, 'File already exists');
                $output->writeln("<warning>File already exists: {$copyFile->fullSource()} -> {$copyFile->fullTarget()}</warning>");

                return;
            }

            $this->filesystem->copy($copyFile->fullSource(), $copyFile->fullTarget());

            if ($preserveTimestamps) {
                $this->filesystem->touch($copyFile->fullTarget(), $copyFile->mtime(), $copyFile->atime());
            }

            $journal->addCopiedFile($copyFile);

            $output->writeln("Copied: {$copyFile->fullSource()} -> {$copyFile->fullTarget()}");
        });
    }
}
