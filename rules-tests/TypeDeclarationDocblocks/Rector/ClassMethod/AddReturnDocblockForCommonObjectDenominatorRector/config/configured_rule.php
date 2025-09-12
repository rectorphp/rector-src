<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector;

return RectorConfig::configure()
    ->withRules([AddReturnDocblockForCommonObjectDenominatorRector::class]);
