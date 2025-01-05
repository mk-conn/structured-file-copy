<?php

declare(strict_types=1);

namespace MkConn\Sfc\Enums;

enum SortOption: string {
    case DATE_YEAR = 'by:date:year';
    case DATE_MONTH = 'by:date:month';
    case DATE_DAY = 'by:date:day';
    case DEFAULT = 'by:default';
    case BY_LETTER = 'by:letter';
    case FILE_TYPE = 'by:file:type';
}
