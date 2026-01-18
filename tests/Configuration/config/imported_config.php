<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector;

return RectorConfig::configure()
    ->withRules([ReturnTypeFromReturnNewRector::class]);
