<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnArrayDocblockBasedOnArrayMapRector;

return RectorConfig::configure()
    ->withRules([AddReturnArrayDocblockBasedOnArrayMapRector::class]);
