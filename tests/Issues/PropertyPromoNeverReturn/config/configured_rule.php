<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        ClassPropertyAssignToConstructorPromotionRector::class,
        ReturnNeverTypeRector::class,
    ]);
};