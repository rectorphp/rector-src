<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeFromStrictMethodCallPassRector;

return RectorConfig::configure()
    ->withRules([AddParamTypeFromStrictMethodCallPassRector::class]);
