<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector;
use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersionFeature;

return RectorConfig::configure()
    ->withRules([ArrowFunctionDelegatingCallToFirstClassCallableRector::class])
    ->withPhpVersion(PhpVersionFeature::FIRST_CLASS_CALLABLE_SYNTAX);
