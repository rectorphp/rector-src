<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Property\SplitGroupedPropertiesRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([SplitGroupedPropertiesRector::class]);
