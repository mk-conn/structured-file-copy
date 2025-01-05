<?php

declare(strict_types=1);

namespace Tests\Unit\Strategies;

use Illuminate\Support\Collection;
use InvalidArgumentException;
use MkConn\Sfc\Enums\SortOption;
use MkConn\Sfc\Strategies\AvailableStrategies;
use MkConn\Sfc\Strategies\Copy\ByLetterStrategy;
use MkConn\Sfc\Strategies\Copy\DateStrategy\YearStrategy;
use MkConn\Sfc\Strategies\Copy\DefaultCopyStrategy;
use PHPUnit\Framework\TestCase;

final class AvailableStrategiesTest extends TestCase {
    public function testGetAllAvailableStrategies(): void {
        $strategies = new Collection();
        $strategies->put(SortOption::BY_LETTER->value, new ByLetterStrategy());
        $strategies->put(SortOption::DEFAULT->value, new DefaultCopyStrategy());
        $strategies->put(SortOption::DATE_YEAR->value, new YearStrategy());
        $availableStrategies = new AvailableStrategies($strategies);
        $allStrategies = $availableStrategies->getStrategies();

        self::assertCount(3, $allStrategies);
    }

    public function testGetStrategyBySortOption(): void {
        $strategies = new Collection();
        $strategies->put(SortOption::BY_LETTER->value, new ByLetterStrategy());
        $strategies->put(SortOption::DEFAULT->value, new DefaultCopyStrategy());
        $strategies->put(SortOption::DATE_YEAR->value, new YearStrategy());
        $availableStrategies = new AvailableStrategies($strategies);

        $strategy = $availableStrategies->strategyForSortOption(SortOption::DEFAULT);

        self::assertInstanceOf(DefaultCopyStrategy::class, $strategy);
    }

    public function testThrowsExceptionWhenStrategyNotFound(): void {
        $availableStrategies = new AvailableStrategies(collect());

        self::expectException(InvalidArgumentException::class);
        $availableStrategies->strategyForSortOption(SortOption::DATE_DAY);
    }
}
