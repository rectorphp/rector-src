<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\BooleanNot\SimplifyDeMorganBinaryRector;
use Rector\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector;
use Rector\Config\RectorConfig;
use Rector\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector;

return RectorConfig::configure()->withRules(
    [SimplifyIfReturnBoolRector::class, SimplifyDeMorganBinaryRector::class, DisallowedEmptyRuleFixerRector::class]
);
