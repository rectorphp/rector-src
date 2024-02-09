<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php70\Rector\Assign\ListSplitStringRector;

return RectorConfig::configure()->withRules([ListSplitStringRector::class]);
