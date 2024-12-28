<?php

declare(strict_types=1);

namespace MkConn\Sfc\Strategies\Copy;

use MkConn\Sfc\Strategies\WithStrategyOptionsInterface;
use SplFileInfo;

class ByLetterStrategy extends GroupStrategy implements WithStrategyOptionsInterface {
    public const string OPTION_BY_LETTER = 'by-letter';
    private int $substrLength = 1;

    public function getGroupKey(SplFileInfo $file): string {
        return substr(pathinfo($file->getFilename(), PATHINFO_FILENAME), 0, $this->substrLength);
    }

    public function withOptions(array $options): static {
        $clone = clone $this;
        $clone->substrLength = (int) $options[self::OPTION_BY_LETTER] ?: $this->substrLength;

        return $clone;
    }

    public function availableOptions(): ?array {
        return [
            self::OPTION_BY_LETTER => [
                'description' => 'Length of the first letters to group by',
                'default'     => $this->substrLength,
            ]];
    }
}
