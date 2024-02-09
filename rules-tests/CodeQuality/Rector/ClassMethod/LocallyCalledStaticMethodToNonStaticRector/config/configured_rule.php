<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([LocallyCalledStaticMethodToNonStaticRector::class]);
