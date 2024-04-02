<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Property\RemoveUselessReadOnlyDocRector;

return RectorConfig::configure()
    ->withRules([RemoveUselessReadOnlyDocRector::class]);
