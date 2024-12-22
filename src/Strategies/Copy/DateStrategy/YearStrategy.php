<?php

declare(strict_types=1);

namespace MkConn\Sfc\Strategies\Copy\DateStrategy;

use MkConn\Sfc\Strategies\Copy\DateStrategy;
use SplFileInfo;

class YearStrategy extends DateStrategy {
    protected function getDatePart(SplFileInfo $file): string {
        return date('Y', $file->getMTime());
    }
}
