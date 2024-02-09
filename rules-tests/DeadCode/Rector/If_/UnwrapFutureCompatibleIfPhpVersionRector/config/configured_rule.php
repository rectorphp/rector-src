<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\If_\UnwrapFutureCompatibleIfPhpVersionRector;

return RectorConfig::configure()->withRules([UnwrapFutureCompatibleIfPhpVersionRector::class]);
