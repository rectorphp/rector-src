<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\KnownMagicClassMethodTypeRector;
use Rector\ValueObject\PhpVersionFeature;

return RectorConfig::configure()
    ->withRules([KnownMagicClassMethodTypeRector::class])
    ->withPhpVersion(PhpVersionFeature::MIXED_TYPE);
