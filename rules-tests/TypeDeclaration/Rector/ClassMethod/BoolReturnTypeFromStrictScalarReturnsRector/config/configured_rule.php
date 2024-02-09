<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromStrictScalarReturnsRector;

return RectorConfig::configure()->withRules([BoolReturnTypeFromStrictScalarReturnsRector::class]);
