<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php70\Rector\FuncCall\MultiDirnameRector;

return RectorConfig::configure()
    ->withRules([MultiDirnameRector::class]);
