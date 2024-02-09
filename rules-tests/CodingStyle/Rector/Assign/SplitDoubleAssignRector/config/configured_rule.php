<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Assign\SplitDoubleAssignRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([SplitDoubleAssignRector::class]);
