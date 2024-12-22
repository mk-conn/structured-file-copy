<?php

declare(strict_types=1);

namespace MkConn\Sfc\Enums;

use InvalidArgumentException;

enum SortOption: string {
    case DATE_YEAR = 'date:year';
    case DATE_MONTH = 'date:month';
    case DATE_DAY = 'date:day';
    case ALPHA_NAME = 'alpha:name';
    case NAME_LETTERS = 'name:letters';
    case FILE_TYPE = 'file:type';

    public static function fromString(?string $sortOption = ''): SortOption {
        return match ($sortOption) {
            'date:year'    => self::DATE_YEAR,
            'date:month'   => self::DATE_MONTH,
            'date:day'     => self::DATE_DAY,
            'name:letters' => self::NAME_LETTERS,
            'file:type'    => self::FILE_TYPE,
            'alpha:name'   => self::ALPHA_NAME,
            default        => throw new InvalidArgumentException('Invalid sort option'),
        };
    }
}
