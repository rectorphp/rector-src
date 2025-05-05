<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\NetteUtils\Rector\StaticCall\UtilsJsonStaticCallNamedArgRector;

return RectorConfig::configure()
    ->withRules([UtilsJsonStaticCallNamedArgRector::class]);
