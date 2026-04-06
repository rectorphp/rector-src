<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Ternary\RemoveUselessTernaryRector;

return RectorConfig::configure()
    ->withRules([RemoveUselessTernaryRector::class]);
