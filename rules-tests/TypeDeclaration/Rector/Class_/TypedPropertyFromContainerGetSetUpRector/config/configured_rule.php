<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Class_\TypedPropertyFromContainerGetSetUpRector;
use Rector\ValueObject\PhpVersionFeature;

return RectorConfig::configure()
    ->withRules([TypedPropertyFromContainerGetSetUpRector::class])
    ->withPhpVersion(PhpVersionFeature::TYPED_PROPERTIES);
