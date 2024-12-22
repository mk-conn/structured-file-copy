<?php

declare(strict_types=1);

namespace MkConn\Sfc\Exceptions;

use Exception;

class FileCopyException extends Exception {
    public function __construct(string $source, string $target, string $message = '') {
        parent::__construct("Failed to copy file from $source to $target - $message");
    }
}
