<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessAssignFromPropertyPromotionRector;

return RectorConfig::configure()
    ->withRules([RemoveUselessAssignFromPropertyPromotionRector::class]);
