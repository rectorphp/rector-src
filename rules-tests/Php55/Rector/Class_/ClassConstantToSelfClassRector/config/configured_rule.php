<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php55\Rector\Class_\ClassConstantToSelfClassRector;

return RectorConfig::configure()
    ->withRules([ClassConstantToSelfClassRector::class]);
