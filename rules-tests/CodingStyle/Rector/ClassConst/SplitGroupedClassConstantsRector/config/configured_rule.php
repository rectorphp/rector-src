<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ClassConst\SplitGroupedClassConstantsRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(SplitGroupedClassConstantsRector::class);
};
