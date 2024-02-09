<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Privatization\Rector\ClassMethod\PrivatizeFinalClassMethodRector;

return RectorConfig::configure()->withRules([PrivatizeFinalClassMethodRector::class]);
