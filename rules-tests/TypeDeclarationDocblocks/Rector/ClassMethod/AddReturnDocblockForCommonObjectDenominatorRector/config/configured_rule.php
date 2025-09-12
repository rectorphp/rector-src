<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules(
        [\Rector\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForCommonObjectDenominatorRector::class]
    );
