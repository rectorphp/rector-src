<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnDocblockForScalarArrayFromAssignsRector;

return RectorConfig::configure()
    ->withRules([AddReturnDocblockForScalarArrayFromAssignsRector::class]);
