<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassLike\RemoveMockObjectPropertyAnnotationRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(RemoveMockObjectPropertyAnnotationRector::class);
};
