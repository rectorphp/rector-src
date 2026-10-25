<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Closure\ClosureDelegatingCallToFirstClassCallableRector;
use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withRules([ClosureDelegatingCallToFirstClassCallableRector::class])
    ->withPhpVersion(PhpVersion::PHP_81);
