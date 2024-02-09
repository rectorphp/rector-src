<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector;

return RectorConfig::configure()->withRules([RemoveUselessReturnTagRector::class]);
