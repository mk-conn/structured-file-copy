<?php

declare(strict_types=1);

namespace MkConn\Sfc\Models;

use MkConn\Sfc\Enums\FilterType;

readonly class Filter {
    public function __construct(public FilterType $filterType, public string $value) {}
}
