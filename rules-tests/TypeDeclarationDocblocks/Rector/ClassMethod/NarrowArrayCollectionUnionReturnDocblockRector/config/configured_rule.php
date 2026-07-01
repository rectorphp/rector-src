<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclarationDocblocks\Rector\ClassMethod\NarrowArrayCollectionUnionReturnDocblockRector;

return RectorConfig::configure()
    ->withRules([NarrowArrayCollectionUnionReturnDocblockRector::class]);
