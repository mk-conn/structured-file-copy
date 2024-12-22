<?php

declare(strict_types=1);

namespace MkConn\Sfc\Models;

use Illuminate\Support\Collection;
use MkConn\Sfc\Enums\SortOption;

readonly class Sort {
    /**
     * @param Collection<array-key, SortOption> $sortOptions
     */
    public function __construct(private Collection $sortOptions = new Collection()) {}

    /**
     * @param array<string> $sortOptions
     */
    public static function createFromArray(array $sortOptions = []): Sort {
        if (empty($sortOptions)) {
            $options = [SortOption::ALPHA_NAME];
        } else {
            $options = array_map(fn ($option) => SortOption::fromString($option), $sortOptions);
        }

        return new Sort(collect($options));
    }

    public static function create(): Sort {
        return self::createFromArray();
    }

    /**
     * @return Collection<array-key, SortOption>
     */
    public function sortOptions(): Collection {
        return $this->sortOptions;
    }
}
