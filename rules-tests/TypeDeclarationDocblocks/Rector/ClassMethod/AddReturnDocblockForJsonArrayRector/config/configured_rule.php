<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclarationDocblocks\Rector\ClassMethod\AddReturnDocblockForJsonArrayRector;

return RectorConfig::configure()
    ->withRules([AddReturnDocblockForJsonArrayRector::class]);
