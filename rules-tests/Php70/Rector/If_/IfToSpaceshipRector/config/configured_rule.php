<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php70\Rector\If_\IfToSpaceshipRector;

return RectorConfig::configure()
    ->withRules([IfToSpaceshipRector::class]);
