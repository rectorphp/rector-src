<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\ShortenElseIfRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([ShortenElseIfRector::class]);
