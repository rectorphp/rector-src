<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Class_\TypedPropertyFromCreateMockAssignRector;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withRules([TypedPropertyFromCreateMockAssignRector::class])
    ->withPhpVersion(PhpVersion::PHP_74);
