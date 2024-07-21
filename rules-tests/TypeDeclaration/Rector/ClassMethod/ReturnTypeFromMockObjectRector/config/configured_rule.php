<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromMockObjectRector;

return RectorConfig::configure()
    ->withRules([ReturnTypeFromMockObjectRector::class]);
