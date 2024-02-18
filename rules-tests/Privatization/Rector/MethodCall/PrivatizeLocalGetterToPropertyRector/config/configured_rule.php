<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Privatization\Rector\MethodCall\PrivatizeLocalGetterToPropertyRector;

return RectorConfig::configure()
    ->withRules([PrivatizeLocalGetterToPropertyRector::class]);
