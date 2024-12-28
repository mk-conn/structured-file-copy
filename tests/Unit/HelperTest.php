<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\DataProvider;

use Tests\SfcTestCase;

use function MkConn\Sfc\humanFilesize;

class HelperTest extends SfcTestCase {
    #[DataProvider('humanFilesizeDataProvider')]
    public function testHumanFileSize(int $byte, string $expected): void {
        self::assertSame($expected, humanFilesize($byte));
    }

    /**
     * @return array{Byte: array{0: int, 1: string}, KiloByte: array{0: int, 1: string}, MegaByte: array{0: float|int, 1: string}, GigaByte: array{0: float|int, 1: string}, TeraByte: array{0: float|int, 1: string}, PetaByte: array{0: float|int, 1: string}}
     */
    public static function humanFilesizeDataProvider(): array {
        return [
            'Byte'     => [1, '1.00B'],
            'KiloByte' => [1024, '1.00K'],
            'MegaByte' => [1024 * 1024, '1.00M'],
            'GigaByte' => [1024 * 1024 * 1024, '1.00G'],
            'TeraByte' => [1024 * 1024 * 1024 * 1024, '1.00T'],
            'PetaByte' => [1024 * 1024 * 1024 * 1024 * 1024, '1.00P'],
        ];
    }
}
