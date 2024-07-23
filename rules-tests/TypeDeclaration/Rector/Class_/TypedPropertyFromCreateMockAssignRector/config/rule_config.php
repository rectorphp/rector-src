<?php

declare(strict_types=1);
use Rector\ValueObject\PhpVersionFeature;

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Class_\TypedPropertyFromCreateMockAssignRector;

return RectorConfig::configure()
    ->withRules([TypedPropertyFromCreateMockAssignRector::class])
    ->withPhpVersion(PhpVersionFeature::TYPED_PROPERTIES);
