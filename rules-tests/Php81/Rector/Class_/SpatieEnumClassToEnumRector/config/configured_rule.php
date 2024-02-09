<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php81\Rector\Class_\SpatieEnumClassToEnumRector;

return RectorConfig::configure()->withRules([SpatieEnumClassToEnumRector::class]);
