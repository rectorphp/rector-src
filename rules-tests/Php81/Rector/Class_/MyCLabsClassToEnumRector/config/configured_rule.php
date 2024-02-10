<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php81\Rector\Class_\MyCLabsClassToEnumRector;

return RectorConfig::configure()
    ->withRules([MyCLabsClassToEnumRector::class]);
