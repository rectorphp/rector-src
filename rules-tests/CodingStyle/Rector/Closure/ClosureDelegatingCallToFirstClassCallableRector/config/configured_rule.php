<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Closure\ClosureDelegatingCallToFirstClassCallableRector;
use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersionFeature;

return RectorConfig::configure()
    ->withRules([ClosureDelegatingCallToFirstClassCallableRector::class])
    ->withPhpVersion(PhpVersionFeature::FIRST_CLASS_CALLABLE_SYNTAX);
