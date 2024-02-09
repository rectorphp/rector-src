<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\If_\SimplifyIfElseWithSameContentRector;

return RectorConfig::configure()->withRules([SimplifyIfElseWithSameContentRector::class]);
