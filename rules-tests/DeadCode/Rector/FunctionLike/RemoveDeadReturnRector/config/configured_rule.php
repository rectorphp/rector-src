<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\FunctionLike\RemoveDeadReturnRector;

return RectorConfig::configure()->withRules([RemoveDeadReturnRector::class]);
