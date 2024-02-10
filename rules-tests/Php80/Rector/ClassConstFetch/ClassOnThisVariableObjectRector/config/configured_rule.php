<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\ClassConstFetch\ClassOnThisVariableObjectRector;

return RectorConfig::configure()
    ->withRules([ClassOnThisVariableObjectRector::class]);
