<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\CallLike\AddNameToBooleanArgumentRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([AddNameToBooleanArgumentRector::class]);
