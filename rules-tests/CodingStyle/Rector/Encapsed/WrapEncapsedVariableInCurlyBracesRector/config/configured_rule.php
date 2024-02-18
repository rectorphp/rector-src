<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Encapsed\WrapEncapsedVariableInCurlyBracesRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([WrapEncapsedVariableInCurlyBracesRector::class]);
