<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php52\Rector\Switch_\ContinueToBreakInSwitchRector;
use Rector\Php70\Rector\Break_\BreakNotInLoopOrSwitchToReturnRector;

return RectorConfig::configure()
    ->withRules([BreakNotInLoopOrSwitchToReturnRector::class, ContinueToBreakInSwitchRector::class]);
