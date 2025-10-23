<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Concat\DirnameDirConcatStringToDirectStringPathRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([DirnameDirConcatStringToDirectStringPathRector::class]);
};
