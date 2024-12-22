<?php

declare(strict_types=1);

namespace MkConn\Sfc\Models;

use Illuminate\Support\Collection;
use MkConn\Sfc\Enums\BucketType;

readonly class Bucket {
    /**
     * @param ?Collection<array-key, CopyFile> $files
     * @param ?Collection<array-key, Bucket>   $buckets
     */
    public function __construct(
        private string $name,
        private BucketType $bucketType,
        private ?Collection $files = new Collection(),
        private ?Collection $buckets = new Collection()
    ) {}

    /**
     * @return Collection<array-key, CopyFile>
     */
    public function files(): Collection {
        return $this->files ?? new Collection();
    }

    /**
     * @return Collection<array-key, Bucket>
     */
    public function buckets(): Collection {
        return $this->buckets ?? new Collection();
    }

    public function type(): BucketType {
        return $this->bucketType;
    }

    public function name(): string {
        return $this->name;
    }
}
