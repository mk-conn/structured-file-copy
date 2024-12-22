<?php

declare(strict_types=1);

namespace MkConn\Sfc\Enums;

enum BucketType {
    case YEAR;
    case MONTH;
    case DAY;
    case ALPHA_NAME;
    case FILE_TYPE;
    case FOLDER;
}
