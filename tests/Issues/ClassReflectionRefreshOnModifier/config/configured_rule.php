<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Tests\Issues\ClassReflectionRefreshOnModifier\Source\FinalizeByReflectionRector;
use Rector\Tests\Issues\ClassReflectionRefreshOnModifier\Source\ReadonlyOnFinalByReflectionRector;

return RectorConfig::configure()
    ->withRules([FinalizeByReflectionRector::class, ReadonlyOnFinalByReflectionRector::class]);
