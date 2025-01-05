<?php

declare(strict_types=1);

namespace MkConn\Sfc\Strategies\Copy\DateStrategy;

use MkConn\Sfc\Strategies\Copy\DateStrategy;
use SplFileInfo;

class DayStrategy extends DateStrategy {
    protected function getDatePart(SplFileInfo $file): string {
        return date('d', $file->getMTime());
    }
}
