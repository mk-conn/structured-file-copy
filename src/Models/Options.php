<?php

declare(strict_types=1);

namespace MkConn\Sfc\Models;

readonly class Options {
    public function __construct(
        public string $source,
        public string $target,
        public ?Sort $sort,
        public ?string $onlyExtension = '',
        public ?string $onlyFileType = '',
        public ?string $exludeExtension = ''
    ) {}
}
