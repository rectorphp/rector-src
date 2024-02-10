<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Identical\BooleanNotIdenticalToNotIdenticalRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([BooleanNotIdenticalToNotIdenticalRector::class]);
