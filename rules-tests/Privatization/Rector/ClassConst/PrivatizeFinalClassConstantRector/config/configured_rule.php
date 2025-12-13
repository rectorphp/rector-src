<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Privatization\Rector\ClassConst\PrivatizeFinalClassConstantRector;

return RectorConfig::configure()
    ->withRules([PrivatizeFinalClassConstantRector::class]);
