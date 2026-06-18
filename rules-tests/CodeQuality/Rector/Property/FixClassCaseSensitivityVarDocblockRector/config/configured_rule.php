<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Property\FixClassCaseSensitivityVarDocblockRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([FixClassCaseSensitivityVarDocblockRector::class]);
