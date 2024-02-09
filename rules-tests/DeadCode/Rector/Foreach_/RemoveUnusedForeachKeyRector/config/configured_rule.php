<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Foreach_\RemoveUnusedForeachKeyRector;

return RectorConfig::configure()->withRules([RemoveUnusedForeachKeyRector::class]);
