<?php

declare(strict_types=1);

use Rector\ValueObject\PhpVersionFeature;
use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\KnownMagicClassMethodTypeRector;

return RectorConfig::configure()
    ->withRules([KnownMagicClassMethodTypeRector::class])
    ->withPhpVersion(PhpVersionFeature::MIXED_TYPE);
