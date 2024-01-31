<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Privatization\Rector\Class_\FinalizeTestCaseClassRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(FinalizeTestCaseClassRector::class);
};
