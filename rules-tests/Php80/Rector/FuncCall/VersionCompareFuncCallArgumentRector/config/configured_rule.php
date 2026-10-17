<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\FuncCall\VersionCompareFuncCallArgumentRector;

return RectorConfig::configure()
    ->withRules([VersionCompareFuncCallArgumentRector::class]);
