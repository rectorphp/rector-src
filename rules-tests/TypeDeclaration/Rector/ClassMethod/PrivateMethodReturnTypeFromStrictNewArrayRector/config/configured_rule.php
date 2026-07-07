<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\PrivateMethodReturnTypeFromStrictNewArrayRector;

return RectorConfig::configure()
    ->withRules([PrivateMethodReturnTypeFromStrictNewArrayRector::class]);
