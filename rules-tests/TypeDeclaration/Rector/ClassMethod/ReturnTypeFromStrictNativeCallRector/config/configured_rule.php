<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNativeCallRector;
use Rector\ValueObject\PhpVersionFeature;

return RectorConfig::configure()
    ->withRules([ReturnTypeFromStrictNativeCallRector::class])
    ->withPhpVersion(PhpVersionFeature::NULL_FALSE_TRUE_STANDALONE_TYPE);
