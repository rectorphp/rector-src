<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Tests\Issues\ClassReflectionRefresh\Source\FinalizeByReflectionRector;
use Rector\Tests\Issues\ClassReflectionRefresh\Source\ReadonlyOnFinalByReflectionRector;

return RectorConfig::configure()
    ->withRules([FinalizeByReflectionRector::class, ReadonlyOnFinalByReflectionRector::class]);
