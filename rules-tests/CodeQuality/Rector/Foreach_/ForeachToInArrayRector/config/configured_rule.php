<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Foreach_\ForeachToInArrayRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([ForeachToInArrayRector::class]);
