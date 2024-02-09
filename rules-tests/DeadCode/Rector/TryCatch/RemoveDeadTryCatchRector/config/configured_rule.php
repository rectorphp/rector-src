<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\TryCatch\RemoveDeadTryCatchRector;

return RectorConfig::configure()->withRules([RemoveDeadTryCatchRector::class]);
