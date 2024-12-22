<?php

declare(strict_types=1);

namespace MkConn\Sfc\Tests\Unit\Models;

use InvalidArgumentException;
use MkConn\Sfc\Enums\SortOption;
use MkConn\Sfc\Models\Sort;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class SortTest extends TestCase {
    /**
     * @param array<string> $expected
     * @param array<string> $provided
     */
    #[DataProvider('sortDataProvider')]
    public function testCreateSort(array $provided, array $expected): void {
        $sort = Sort::createFromArray($provided);
        self::assertSame($expected, $sort->sortOptions()->all());
    }

    public function testThrowsOnInvalidSortOption(): void {
        self::expectException(InvalidArgumentException::class);
        Sort::createFromArray(['date:year', 'invalid']);
    }

    /**
     * @return array<string, array{0: array<string>, 1: array<SortOption>}>
     */
    public static function sortDataProvider(): array {
        return [
            'by date-year-day' => [['date:year', 'date:month', 'date:day'], [SortOption::DATE_YEAR, SortOption::DATE_MONTH, SortOption::DATE_DAY]],
            'by name-letters'  => [['name:letters'], [SortOption::NAME_LETTERS]],
            'empty'            => [[], [SortOption::ALPHA_NAME]],
        ];
    }
}
