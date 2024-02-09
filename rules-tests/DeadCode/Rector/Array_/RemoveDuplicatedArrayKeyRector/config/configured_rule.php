<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Array_\RemoveDuplicatedArrayKeyRector;

return RectorConfig::configure()->withRules([RemoveDuplicatedArrayKeyRector::class]);
