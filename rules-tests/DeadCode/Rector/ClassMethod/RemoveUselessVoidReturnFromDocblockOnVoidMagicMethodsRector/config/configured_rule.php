<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\ClassMethod\RemoveUselessVoidReturnFromDocblockVoidMagicMethodsRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([RemoveUselessVoidReturnFromDocblockVoidMagicMethodsRector::class]);
