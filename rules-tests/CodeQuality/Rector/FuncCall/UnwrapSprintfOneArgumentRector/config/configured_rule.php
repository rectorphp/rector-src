<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FuncCall\UnwrapSprintfOneArgumentRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([UnwrapSprintfOneArgumentRector::class]);
