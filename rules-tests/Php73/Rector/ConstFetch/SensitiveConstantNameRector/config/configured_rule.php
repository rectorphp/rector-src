<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php73\Rector\ConstFetch\SensitiveConstantNameRector;

return RectorConfig::configure()
    ->withRules([SensitiveConstantNameRector::class]);
