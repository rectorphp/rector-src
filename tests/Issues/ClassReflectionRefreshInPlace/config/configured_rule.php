<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Tests\Issues\ClassReflectionRefresh\Source\AddTraitByReflectionRector;
use Rector\Tests\Issues\ClassReflectionRefreshInPlace\Source\AddOtherTraitByReflectionRector;

return RectorConfig::configure()
    ->withRules([AddTraitByReflectionRector::class, AddOtherTraitByReflectionRector::class]);
