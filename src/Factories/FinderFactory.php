<?php

declare(strict_types=1);

namespace MkConn\Sfc\Factories;

use Symfony\Component\Finder\Finder;

class FinderFactory {
    public function create(): Finder {
        return Finder::create();
    }
}
