<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FuncCall\InlineIsAInstanceOfRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([InlineIsAInstanceOfRector::class]);
