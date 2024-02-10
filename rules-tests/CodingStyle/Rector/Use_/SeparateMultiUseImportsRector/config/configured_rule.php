<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Use_\SeparateMultiUseImportsRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([SeparateMultiUseImportsRector::class]);
