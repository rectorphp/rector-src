<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\StaticCall\RemoveParentCallWithoutParentRector;

return RectorConfig::configure()->withRules([RemoveParentCallWithoutParentRector::class]);
