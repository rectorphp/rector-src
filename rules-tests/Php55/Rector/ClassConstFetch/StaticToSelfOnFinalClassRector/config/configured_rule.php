<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php55\Rector\ClassConstFetch\StaticToSelfOnFinalClassRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(StaticToSelfOnFinalClassRector::class);
};
