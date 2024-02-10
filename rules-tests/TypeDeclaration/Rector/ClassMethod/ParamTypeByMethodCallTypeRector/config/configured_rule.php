<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector;

return RectorConfig::configure()
    ->withRules([ParamTypeByMethodCallTypeRector::class]);
