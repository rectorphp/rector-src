<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Identical\StrlenZeroToIdenticalEmptyStringRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([StrlenZeroToIdenticalEmptyStringRector::class]);
