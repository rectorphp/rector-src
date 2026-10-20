<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Tests\Issues\ClassReflectionRefresh\Source\AddSomeTraitRector;
use Rector\Tests\Issues\ClassReflectionRefresh\Source\FinalizeOnReflectedTraitRector;

return RectorConfig::configure()
    ->withRules([AddSomeTraitRector::class, FinalizeOnReflectedTraitRector::class]);
