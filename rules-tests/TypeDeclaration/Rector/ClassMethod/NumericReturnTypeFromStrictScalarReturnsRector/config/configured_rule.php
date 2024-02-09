<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector;

return RectorConfig::configure()->withRules([NumericReturnTypeFromStrictScalarReturnsRector::class]);
