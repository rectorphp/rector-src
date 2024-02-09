<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Strict\Rector\If_\BooleanInIfConditionRuleFixerRector;

return RectorConfig::configure()->withRules([BooleanInIfConditionRuleFixerRector::class]);
