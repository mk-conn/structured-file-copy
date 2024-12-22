<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use MkConn\Sfc\Strategies\Copy\AlphaNumericStrategy;
use MkConn\Sfc\Strategies\Copy\DateStrategy\DayStrategy;
use MkConn\Sfc\Strategies\Copy\DateStrategy\MonthStrategy;
use MkConn\Sfc\Strategies\Copy\DateStrategy\YearStrategy;
use MkConn\Sfc\Strategies\Copy\FileTypeStrategy;
use MkConn\Sfc\Strategies\StrategyCollection;

return [
    StrategyCollection::class => function (Psr\Container\ContainerInterface $c) {
        return new StrategyCollection(new Collection([
            $c->get(AlphaNumericStrategy::class),
            $c->get(DayStrategy::class),
            $c->get(FileTypeStrategy::class),
            $c->get(MonthStrategy::class),
            $c->get(YearStrategy::class),
        ]));
    },
];
