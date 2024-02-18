<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnDirectArrayRector;

return RectorConfig::configure()
    ->withRules([ReturnTypeFromReturnDirectArrayRector::class]);
