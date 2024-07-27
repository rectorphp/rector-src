<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Class_\TypedPropertyFromCreateMockAssignRector;
use Rector\ValueObject\PhpVersionFeature;

return RectorConfig::configure()
    ->withRules([TypedPropertyFromCreateMockAssignRector::class])
    ->withPhpVersion(PhpVersionFeature::TYPED_PROPERTIES);
