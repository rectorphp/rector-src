<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector;

return RectorConfig::configure()
    ->withRules([RenameVariableToMatchMethodCallReturnTypeRector::class]);
