<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForDimFetchArrayFromAssignsRector;

return RectorConfig::configure()
    ->withRules([AddReturnDocblockForDimFetchArrayFromAssignsRector::class]);
