<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Class_\TypedPropertyFromGetRepositorySetUpRector;
use Rector\ValueObject\PhpVersionFeature;

return RectorConfig::configure()
    ->withRules([TypedPropertyFromGetRepositorySetUpRector::class])
    ->withPhpVersion(PhpVersionFeature::TYPED_PROPERTIES);
