<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\FunctionLike\FunctionLikeToFirstClassCallableRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([FunctionLikeToFirstClassCallableRector::class]);
