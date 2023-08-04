<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(SeparateMultiUseImportsRector::class);
    $rectorConfig->rule(ClassPropertyAssignToConstructorPromotionRector::class);
    $rectorConfig->rule(FinalizeClassesWithoutChildrenRector::class);
};
