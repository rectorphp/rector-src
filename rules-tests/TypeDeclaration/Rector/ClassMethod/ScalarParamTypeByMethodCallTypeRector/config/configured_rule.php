<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\ScalarParamTypeByMethodCallTypeRector;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withRules([ScalarParamTypeByMethodCallTypeRector::class])
    ->withPhpVersion(PhpVersion::PHP_80);
