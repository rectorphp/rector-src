<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withAttributesSets(symfony: true, doctrine: true);
