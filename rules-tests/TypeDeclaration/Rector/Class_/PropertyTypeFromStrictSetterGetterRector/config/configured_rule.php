<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Class_\PropertyTypeFromStrictSetterGetterRector;

return RectorConfig::configure()
    ->withRules([PropertyTypeFromStrictSetterGetterRector::class]);
