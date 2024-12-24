<?php

declare(strict_types=1);

namespace MkConn\Sfc\Services;

use MkConn\Sfc\Enums\BucketType;
use MkConn\Sfc\Exceptions\FileCopyException;
use MkConn\Sfc\Factories\FileFinderFactory;
use MkConn\Sfc\Models\Options;
use MkConn\Sfc\Strategies\StrategyPipeline;
use Symfony\Component\Filesystem\Filesystem;

class CopyService {
    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly FileFinderFactory $fileFinderFactory,
        private readonly StrategyPipeline $strategyPipeline,
    ) {}

    /**
     * @throws FileCopyException
     */
    public function copy(Options $options): void {
        if (!$this->filesystem->exists($options->source)) {
            throw new FileCopyException($options->source, $options->target, 'Source folder does not exist');
        }

        $start = $this->bucketFactory->create($options->target, BucketType::FOLDER);
        $sortOptions = $options->sort->sortOptions();
        $files = $this->fileFinderFactory->create($options->source);

        if (0 === $files->count()) {
            throw new FileCopyException($options->source, $options->target, 'No files found in source folder');
        }

        foreach ($files as $file) {
            $filename = $file->getFilename();
            $start->files()->put($filename, $file);
        }

        $break = true;
        //        $sortOptions->map(function ($sortOption): void {
        //            $this->fillBuckets();
        //        });
    }

    private function fillBuckets(): void {
        // Fill buckets with files
    }
}
