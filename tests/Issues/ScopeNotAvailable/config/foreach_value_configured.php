<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Tests\Issues\ScopeNotAvailable\Variable\ArrayItemForeachValueRector;

return RectorConfig::configure()
    ->withRules([ArrayItemForeachValueRector::class]);
