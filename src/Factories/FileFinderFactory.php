<?php

declare(strict_types=1);

namespace MkConn\Sfc\Factories;

use Symfony\Component\Finder\Finder;

class FileFinderFactory {
    public function create(string $source): Finder {
        return (new Finder())->in($source);
    }
}
