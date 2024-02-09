<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\StringableForToStringRector;

return RectorConfig::configure()->withRules([StringableForToStringRector::class]);
