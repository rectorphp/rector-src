<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Class_\TypedPropertyFromDocblockSetUpDefinedRector;
use Rector\ValueObject\PhpVersionFeature;

return RectorConfig::configure()
    ->withRules([TypedPropertyFromDocblockSetUpDefinedRector::class])
    ->withPhpVersion(PhpVersionFeature::TYPED_PROPERTIES);
