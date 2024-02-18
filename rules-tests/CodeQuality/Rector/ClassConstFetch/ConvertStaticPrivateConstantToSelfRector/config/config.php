<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\ClassConstFetch\ConvertStaticPrivateConstantToSelfRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([ConvertStaticPrivateConstantToSelfRector::class]);
