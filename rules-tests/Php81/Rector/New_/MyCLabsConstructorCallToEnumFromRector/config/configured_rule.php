<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php81\Rector\New_\MyCLabsConstructorCallToEnumFromRector;

return RectorConfig::configure()
    ->withRules([MyCLabsConstructorCallToEnumFromRector::class]);
