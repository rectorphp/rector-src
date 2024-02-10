<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassConst\RemoveUnusedPrivateClassConstantRector;

return RectorConfig::configure()
    ->withRules([RemoveUnusedPrivateClassConstantRector::class]);
