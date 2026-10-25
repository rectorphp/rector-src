<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ArrowFunction\ArrowFunctionDelegatingCallToFirstClassCallableRector;
use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withRules([ArrowFunctionDelegatingCallToFirstClassCallableRector::class])
    ->withPhpVersion(PhpVersion::PHP_81);
