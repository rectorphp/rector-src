<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Class_\TypedPropertyFromGetRepositorySetUpRector;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withRules([TypedPropertyFromGetRepositorySetUpRector::class])
    ->withPhpVersion(PhpVersion::PHP_74);
