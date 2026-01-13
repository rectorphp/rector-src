<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveVoidDocblockFromMagicMethodRector;

return RectorConfig::configure()
    ->withRules([RemoveVoidDocblockFromMagicMethodRector::class]);
