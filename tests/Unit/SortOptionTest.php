<?php

declare(strict_types=1);

namespace Tests\Unit;

use MkConn\Sfc\Enums\SortOption;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class SortOptionTest extends TestCase {
    #[DataProvider('sortOptionDataProvider')]
    public function testCreateSortOptionFromString(string $sortOption, SortOption $expected): void {
        self::assertSame($expected, SortOption::from($sortOption));
    }

    /**
     * @return array<string, array{0: string|null, 1: SortOption}>
     */
    public static function sortOptionDataProvider(): array {
        return [
            'by:date:year'                => ['by:date:year', SortOption::DATE_YEAR],
            'by:date:month'               => ['by:date:month', SortOption::DATE_MONTH],
            'by:date:day'                 => ['by:date:day', SortOption::DATE_DAY],
            'by:letters'                  => ['by:letter', SortOption::BY_LETTER],
            'file:type'                   => ['by:file:type', SortOption::FILE_TYPE],
            'default'                     => ['by:default', SortOption::DEFAULT],
        ];
    }
}
