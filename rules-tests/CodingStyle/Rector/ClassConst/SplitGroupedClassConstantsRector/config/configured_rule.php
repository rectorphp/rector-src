<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ClassConst\SplitGroupedClassConstantsRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([SplitGroupedClassConstantsRector::class]);
