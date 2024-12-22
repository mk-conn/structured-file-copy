<?php

declare(strict_types=1);

namespace MkConn\Sfc\Tests\Unit\Models;

use Illuminate\Support\Collection;
use MkConn\Sfc\Enums\BucketType;
use MkConn\Sfc\Models\Bucket;
use PHPUnit\Framework\TestCase;

class BucketTest extends TestCase {
    public function testBucketCreation(): void {
        $yearBucket = new Bucket('2021', BucketType::YEAR, null, new Collection(
            [
                new Bucket('01', BucketType::MONTH),
                new Bucket('02', BucketType::MONTH),
            ]
        ));

        $folderBucket = new Bucket('Target_Folder', BucketType::FOLDER);
        $folderBucket->buckets()->add($yearBucket);

        self::assertEquals('Target_Folder', $folderBucket->name());
        self::assertEquals(BucketType::YEAR, $folderBucket->buckets()->get(0)?->type());
        self::assertEquals('2021', $folderBucket->buckets()->get(0)?->name());
        self::assertEquals(BucketType::MONTH, $folderBucket->buckets()->get(0)?->buckets()->get(0)?->type());
        self::assertSame(2, $folderBucket->buckets()->get(0)?->buckets()->count());
    }
}
