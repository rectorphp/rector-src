<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php52\Rector\Property\VarToPublicPropertyRector;

return RectorConfig::configure()
    ->withRules([VarToPublicPropertyRector::class]);
