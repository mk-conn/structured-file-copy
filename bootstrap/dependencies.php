<?php

declare(strict_types=1);

use DI\Container;
use Illuminate\Support\Collection;
use MkConn\Sfc\Enums\SortOption;
use MkConn\Sfc\Strategies\AvailableStrategies;
use MkConn\Sfc\Strategies\Copy\ByLetterStrategy;
use MkConn\Sfc\Strategies\Copy\DateStrategy\DayStrategy;
use MkConn\Sfc\Strategies\Copy\DateStrategy\MonthStrategy;
use MkConn\Sfc\Strategies\Copy\DateStrategy\YearStrategy;
use MkConn\Sfc\Strategies\Copy\DefaultCopyStrategy;
use MkConn\Sfc\Strategies\Copy\FileTypeStrategy;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Mime\MimeTypesInterface;

use function DI\get;

return [
    AvailableStrategies::class => function (Container $c) {
        $collection = new Collection();
        $collection->put(SortOption::DEFAULT->value, $c->get(DefaultCopyStrategy::class));
        $collection->put(SortOption::BY_LETTER->value, $c->get(ByLetterStrategy::class));
        $collection->put(SortOption::DATE_YEAR->value, $c->get(YearStrategy::class));
        $collection->put(SortOption::DATE_MONTH->value, $c->get(MonthStrategy::class));
        $collection->put(SortOption::DATE_DAY->value, $c->get(DayStrategy::class));
        $collection->put(SortOption::FILE_TYPE->value, $c->get(FileTypeStrategy::class));

        return new AvailableStrategies($collection);
    },
    MimeTypesInterface::class => get(MimeTypes::class),
];
