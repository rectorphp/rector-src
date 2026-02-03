<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector;
use Rector\ValueObject\PhpVersionFeature;

return RectorConfig::configure()
    ->withRules([AddClosureParamTypeForArrayMapRector::class])
    ->withPhpVersion(PhpVersionFeature::UNION_TYPES);
