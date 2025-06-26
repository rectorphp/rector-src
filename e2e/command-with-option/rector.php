<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ConstFetch\RemovePhpVersionIdCheckRector;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/some_file.php'
    ])
    ->withRules([
        StringClassNameToClassConstantRector::class,
        RemovePhpVersionIdCheckRector::class,
    ]);
