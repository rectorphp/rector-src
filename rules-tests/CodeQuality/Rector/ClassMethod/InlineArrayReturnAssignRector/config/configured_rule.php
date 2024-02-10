<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\ClassMethod\InlineArrayReturnAssignRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([InlineArrayReturnAssignRector::class]);
