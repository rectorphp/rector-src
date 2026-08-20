<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php74\Rector\If_\IfToNullCoalescingAssignRector;

return RectorConfig::configure()
    ->withRules([IfToNullCoalescingAssignRector::class]);
