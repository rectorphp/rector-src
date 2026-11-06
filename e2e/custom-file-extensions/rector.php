<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector;

return RectorConfig::configure()
    ->withPaths([__DIR__ . '/src'])
    ->withFileExtensions(['php', 'inc'])
    ->withRules([AddParamBasedOnParentClassMethodRector::class]);
