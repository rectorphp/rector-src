<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\CallLike\NameBooleanOrNullArgumentRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([NameBooleanOrNullArgumentRector::class]);
