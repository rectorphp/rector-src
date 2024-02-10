<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php70\Rector\Variable\WrapVariableVariableNameInCurlyBracesRector;

return RectorConfig::configure()
    ->withRules([WrapVariableVariableNameInCurlyBracesRector::class]);
