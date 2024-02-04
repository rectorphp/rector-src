<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Transform\Rector\ClassMethod\RectorConfigBuilderRector;

return RectorConfig::configure()
    ->withRules([RectorConfigBuilderRector::class]);
