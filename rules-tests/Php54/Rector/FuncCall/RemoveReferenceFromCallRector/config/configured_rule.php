<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php54\Rector\FuncCall\RemoveReferenceFromCallRector;

return RectorConfig::configure()
    ->withRules([RemoveReferenceFromCallRector::class]);
