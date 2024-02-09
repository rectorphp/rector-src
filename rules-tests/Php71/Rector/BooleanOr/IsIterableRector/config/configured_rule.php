<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php71\Rector\BooleanOr\IsIterableRector;

return RectorConfig::configure()->withRules([IsIterableRector::class]);
