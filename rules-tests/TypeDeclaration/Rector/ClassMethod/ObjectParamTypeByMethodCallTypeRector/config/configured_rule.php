<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\ObjectParamTypeByMethodCallTypeRector;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withRules([ObjectParamTypeByMethodCallTypeRector::class])
    ->withPhpVersion(PhpVersion::PHP_80);
