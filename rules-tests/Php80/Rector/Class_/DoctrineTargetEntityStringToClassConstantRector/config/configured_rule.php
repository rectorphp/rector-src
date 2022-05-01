<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\DoctrineTargetEntityStringToClassConstantRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(DoctrineTargetEntityStringToClassConstantRector::class);
};
