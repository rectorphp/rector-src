<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessVoidReturnFromDocblockVoidMagicMethodsRector;

return RectorConfig::configure()
    ->withRules([RemoveUselessVoidReturnFromDocblockVoidMagicMethodsRector::class]);
