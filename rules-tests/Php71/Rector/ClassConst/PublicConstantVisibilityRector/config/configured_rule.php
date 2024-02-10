<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php71\Rector\ClassConst\PublicConstantVisibilityRector;

return RectorConfig::configure()
    ->withRules([PublicConstantVisibilityRector::class]);
