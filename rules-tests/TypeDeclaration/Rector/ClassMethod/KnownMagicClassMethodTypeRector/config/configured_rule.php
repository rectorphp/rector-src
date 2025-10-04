<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\KnownMagicClassMethodTypeRector;

return RectorConfig::configure()
    ->withRules([KnownMagicClassMethodTypeRector::class]);
