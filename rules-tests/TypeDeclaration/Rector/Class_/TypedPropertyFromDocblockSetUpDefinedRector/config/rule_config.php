<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Class_\TypedPropertyFromDocblockSetUpDefinedRector;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withRules([TypedPropertyFromDocblockSetUpDefinedRector::class])
    ->withPhpVersion(PhpVersion::PHP_74);
