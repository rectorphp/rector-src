<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\FunctionLike\TooWideReturnTypeRector;

return RectorConfig::configure()
    ->withRules([TooWideReturnTypeRector::class]);
