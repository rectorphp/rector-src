<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveVoidDocblockFromMagicMethodsRector;

return RectorConfig::configure()
    ->withRules([RemoveVoidDocblockFromMagicMethodsRector::class]);
