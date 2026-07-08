<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\If_\AlternativeIfToBracketRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([AlternativeIfToBracketRector::class]);
