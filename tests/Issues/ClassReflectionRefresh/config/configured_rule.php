<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Tests\Issues\ClassReflectionRefresh\Source\AddTraitByReflectionRector;

return RectorConfig::configure()
    ->withRules([AddTraitByReflectionRector::class]);
