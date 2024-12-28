<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use MkConn\Sfc\Enums\SortOption;
use MkConn\Sfc\Strategies\AvailableStrategies;
use MkConn\Sfc\Strategies\Copy\ByLetterStrategy;
use MkConn\Sfc\Strategies\Copy\DateStrategy\DayStrategy;
use MkConn\Sfc\Strategies\Copy\DateStrategy\MonthStrategy;
use MkConn\Sfc\Strategies\Copy\DateStrategy\YearStrategy;
use MkConn\Sfc\Strategies\Copy\DefaultCopyStrategy;
use MkConn\Sfc\Strategies\Copy\FileTypeStrategy;

return [
    AvailableStrategies::class => function (Psr\Container\ContainerInterface $c) {
        $collection = new Collection();
        $collection->put(SortOption::DEFAULT->name, $c->get(DefaultCopyStrategy::class));
        $collection->put(SortOption::BY_LETTER->name, $c->get(ByLetterStrategy::class));
        $collection->put(SortOption::DATE_YEAR->name, $c->get(YearStrategy::class));
        $collection->put(SortOption::DATE_MONTH->name, $c->get(MonthStrategy::class));
        $collection->put(SortOption::DATE_DAY->name, $c->get(DayStrategy::class));
        $collection->put(SortOption::FILE_TYPE->name, $c->get(FileTypeStrategy::class));

        return new AvailableStrategies($collection);
    },
];
