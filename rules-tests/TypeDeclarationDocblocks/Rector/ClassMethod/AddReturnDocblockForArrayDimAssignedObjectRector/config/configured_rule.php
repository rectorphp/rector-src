<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForArrayDimAssignedObjectRector;

return RectorConfig::configure()
    ->withRules([AddReturnDocblockForArrayDimAssignedObjectRector::class]);
