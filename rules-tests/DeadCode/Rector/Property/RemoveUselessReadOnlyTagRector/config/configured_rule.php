<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Property\RemoveUselessReadOnlyTagRector;

return RectorConfig::configure()
    ->withRules([RemoveUselessReadOnlyTagRector::class]);
