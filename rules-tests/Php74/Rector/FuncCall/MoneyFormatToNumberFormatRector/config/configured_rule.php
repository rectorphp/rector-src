<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php74\Rector\FuncCall\MoneyFormatToNumberFormatRector;

return RectorConfig::configure()
    ->withRules([MoneyFormatToNumberFormatRector::class]);
