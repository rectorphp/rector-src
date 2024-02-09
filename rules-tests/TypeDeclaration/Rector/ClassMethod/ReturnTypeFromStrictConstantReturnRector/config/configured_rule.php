<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictConstantReturnRector;

return RectorConfig::configure()->withRules([ReturnTypeFromStrictConstantReturnRector::class]);
