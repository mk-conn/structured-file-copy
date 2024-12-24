<?php

declare(strict_types=1);

namespace MkConn\Sfc\Strategies\Copy;

use SplFileInfo;

class ByLetterStrategy extends GroupStrategy {
    private int $prefixLength = 1;

    public function getGroupKey(SplFileInfo $file): string {
        return substr(pathinfo($file->getFilename(), PATHINFO_FILENAME), 0, $this->prefixLength);
    }

    public function withPrefixLength(int $prefixLength): ByLetterStrategy {
        $clone = clone $this;
        $clone->prefixLength = $prefixLength;

        return $clone;
    }
}
