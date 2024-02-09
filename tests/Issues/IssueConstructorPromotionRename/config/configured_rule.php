<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Naming\Rector\Class_\RenamePropertyToMatchTypeRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;

return RectorConfig::configure()->withRules(
    [ClassPropertyAssignToConstructorPromotionRector::class, RenamePropertyToMatchTypeRector::class]
);
