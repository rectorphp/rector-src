<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Strict\Rector\BooleanNot\BooleanInBooleanNotRuleFixerRector;

return RectorConfig::configure()
    ->withRules([BooleanInBooleanNotRuleFixerRector::class]);
