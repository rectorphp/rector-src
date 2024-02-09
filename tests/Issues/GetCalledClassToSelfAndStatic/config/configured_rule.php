<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php55\Rector\FuncCall\GetCalledClassToSelfClassRector;
use Rector\Php55\Rector\FuncCall\GetCalledClassToStaticClassRector;

return RectorConfig::configure()->withRules(
    [GetCalledClassToSelfClassRector::class, GetCalledClassToStaticClassRector::class]
);
