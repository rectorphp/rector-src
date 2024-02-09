<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector;

return RectorConfig::configure()->withRules([ReturnTypeFromStrictScalarReturnExprRector::class]);
