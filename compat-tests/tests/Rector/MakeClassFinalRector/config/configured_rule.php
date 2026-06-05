<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\RectorCompatTests\Rector\MakeClassFinalRector;

return RectorConfig::configure()
    ->withRules([MakeClassFinalRector::class]);
