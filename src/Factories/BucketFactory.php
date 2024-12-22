<?php

declare(strict_types=1);

namespace MkConn\Sfc\Factories;

use MkConn\Sfc\Enums\BucketType;
use MkConn\Sfc\Models\Bucket;

class BucketFactory {
    public function create(string $name, BucketType $bucketType): Bucket {
        return new Bucket($name, $bucketType);
    }
}
