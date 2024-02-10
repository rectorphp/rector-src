<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php55\Rector\FuncCall\GetCalledClassToStaticClassRector;

return RectorConfig::configure()
    ->withRules([GetCalledClassToStaticClassRector::class]);
