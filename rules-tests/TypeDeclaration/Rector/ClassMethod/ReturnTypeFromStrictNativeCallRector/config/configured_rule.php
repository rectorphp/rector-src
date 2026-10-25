<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNativeCallRector;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withRules([ReturnTypeFromStrictNativeCallRector::class])
    ->withPhpVersion(PhpVersion::PHP_82);
