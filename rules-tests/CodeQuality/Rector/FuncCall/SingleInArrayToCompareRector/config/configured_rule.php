<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FuncCall\SingleInArrayToCompareRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([SingleInArrayToCompareRector::class]);
