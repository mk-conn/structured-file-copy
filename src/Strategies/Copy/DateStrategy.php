<?php

declare(strict_types=1);

namespace MkConn\Sfc\Strategies\Copy;

use SplFileInfo;

abstract class DateStrategy extends GroupStrategy {
    abstract protected function getDatePart(SplFileInfo $file): string;

    public function getGroupKey(SplFileInfo $file): string {
        return $this->getDatePart($file);
    }
}
