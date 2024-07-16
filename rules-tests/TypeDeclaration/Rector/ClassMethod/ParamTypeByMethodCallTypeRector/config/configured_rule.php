<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withRules([ParamTypeByMethodCallTypeRector::class])
    ->withPhpVersion(PhpVersion::PHP_80);
