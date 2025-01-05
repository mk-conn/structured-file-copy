<?php

declare(strict_types=1);

namespace MkConn\Sfc\Enums;

use InvalidArgumentException;

enum FilterType {
    case EXT;
    case FILE_TYPE;
    case NAME;
    case SIZE;
    case DIRECTORY;

    public static function fromString(string $type): self {
        return match ($type) {
            'ext'       => self::EXT,
            'type'      => self::FILE_TYPE,
            'name'      => self::NAME,
            'size'      => self::SIZE,
            'dir'       => self::DIRECTORY,
            default     => throw new InvalidArgumentException("Unknown filter type: $type"),
        };
    }
}
