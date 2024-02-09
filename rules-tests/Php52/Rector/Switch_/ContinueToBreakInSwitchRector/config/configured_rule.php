<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php52\Rector\Switch_\ContinueToBreakInSwitchRector;

return RectorConfig::configure()->withRules([ContinueToBreakInSwitchRector::class]);
