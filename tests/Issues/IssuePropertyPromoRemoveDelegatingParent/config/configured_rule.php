<?php

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveParentDelegatingConstructorRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;

return RectorConfig::configure()
    ->withRules([
        ClassPropertyAssignToConstructorPromotionRector::class,
        RemoveParentDelegatingConstructorRector::class,
    ]);
