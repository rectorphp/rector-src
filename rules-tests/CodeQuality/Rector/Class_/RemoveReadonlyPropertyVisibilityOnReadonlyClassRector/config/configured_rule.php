<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\RemoveReadonlyPropertyVisibilityOnReadonlyClassRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(RemoveReadonlyPropertyVisibilityOnReadonlyClassRector::class);
};
