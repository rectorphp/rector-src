<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector;

return RectorConfig::configure()
    ->withRules([DocblockReturnArrayFromDirectArrayInstanceRector::class]);
