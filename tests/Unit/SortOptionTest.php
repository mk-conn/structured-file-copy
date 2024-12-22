<?php

declare(strict_types=1);

namespace MkConn\Sfc\Tests\Unit;

use InvalidArgumentException;
use MkConn\Sfc\Enums\SortOption;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class SortOptionTest extends TestCase {
    #[DataProvider('sortOptionDataProvider')]
    public function testCreateSortOptionFromString(?string $sortOption, SortOption $expected): void {
        self::assertSame($expected, SortOption::fromString($sortOption));
    }

    public function testThrowsOnInvalidSortOption(): void {
        $this->expectException(InvalidArgumentException::class);
        SortOption::fromString('invalid');
    }

    /**
     * @return array<string, array{0: string|null, 1: SortOption}>
     */
    public static function sortOptionDataProvider(): array {
        return [
            'date:year'                => ['date:year', SortOption::DATE_YEAR],
            'date:month'               => ['date:month', SortOption::DATE_MONTH],
            'date:day'                 => ['date:day', SortOption::DATE_DAY],
            'name:letters'             => ['name:letters', SortOption::NAME_LETTERS],
            'file:type'                => ['file:type', SortOption::FILE_TYPE],
        ];
    }
}
