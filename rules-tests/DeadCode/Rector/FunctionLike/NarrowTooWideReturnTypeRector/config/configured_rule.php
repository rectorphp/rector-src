<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector;

return RectorConfig::configure()
    ->withRules([NarrowTooWideReturnTypeRector::class]);
