<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Unambiguous\Rector\Expression\FluentSettersToStandaloneCallMethodRector;

return RectorConfig::configure()
    ->withRules([FluentSettersToStandaloneCallMethodRector::class]);
