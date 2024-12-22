<?php

declare(strict_types=1);

namespace MkConn\Sfc\Models;

use Illuminate\Support\Collection;
use MkConn\Sfc\Enums\BucketType;
use SplFileInfo;

/**
 * @template TKey of array-key
 */
interface BucketInterface {
    /**
     * @return Collection<TKey, SplFileInfo>
     */
    public function files(): Collection;

    /**
     * @return Collection<TKey, BucketInterface>
     */
    public function buckets(): Collection;

    public function type(): BucketType;

    public function name(): string;
}
