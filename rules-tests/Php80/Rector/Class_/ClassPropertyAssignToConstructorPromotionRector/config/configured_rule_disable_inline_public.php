<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;

return static function (RectorConfig $rectorConfig): void {
    // inline public is disable by default
    $rectorConfig->rule(ClassPropertyAssignToConstructorPromotionRector::class);
};
