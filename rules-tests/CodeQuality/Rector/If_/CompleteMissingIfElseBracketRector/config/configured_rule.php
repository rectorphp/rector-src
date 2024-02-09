<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\CompleteMissingIfElseBracketRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([CompleteMissingIfElseBracketRector::class]);
