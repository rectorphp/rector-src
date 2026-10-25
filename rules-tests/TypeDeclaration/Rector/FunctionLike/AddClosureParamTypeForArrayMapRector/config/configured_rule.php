<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withRules([AddClosureParamTypeForArrayMapRector::class])
    ->withPhpVersion(PhpVersion::PHP_80);
