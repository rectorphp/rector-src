<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FunctionLike\SimplifyUselessVariableRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([SimplifyUselessVariableRector::class]);
