<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Identical\StrEndsWithRector;

return RectorConfig::configure()
    ->withRules([StrEndsWithRector::class]);
