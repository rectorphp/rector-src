<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector;

return static function (RectorConfig $rectorConfig): void {
    $services = $rectorConfig->services();
    $services->set(FinalizeClassesWithoutChildrenRector::class);
};
