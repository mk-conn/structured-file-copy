<?php

declare(strict_types=1);

namespace MkConn\Sfc\Services\Copy;

use MkConn\Sfc\Services\CopyService;

class DateYear extends CopyService {
    public function copy(): void {
        $files = $this->getFiles();
        $target = $this->options['target'];

        foreach ($files as $file) {
            $date = date('Y', filemtime($file));
            $targetFolder = $target . '/' . $date;

            if (!is_dir($targetFolder)) {
                mkdir($targetFolder);
            }
            copy($file, $targetFolder . '/' . basename($file));
        }
    }
}
