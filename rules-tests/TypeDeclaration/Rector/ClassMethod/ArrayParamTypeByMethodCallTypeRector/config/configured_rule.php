<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\ArrayParamTypeByMethodCallTypeRector;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withRules([ArrayParamTypeByMethodCallTypeRector::class])
    ->withPhpVersion(PhpVersion::PHP_80);
