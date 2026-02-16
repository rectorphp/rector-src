<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php83\Rector\FuncCall\RemoveGetClassGetParentClassNoArgsRector;
use Rector\Php84\Rector\Param\ExplicitNullableParamTypeRector;
use Rector\Php85\Rector\Class_\SleepToSerializeRector;
use Rector\Php85\Rector\FuncCall\ArrayKeyExistsNullToEmptyStringRector;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withRules([
        ArrayKeyExistsNullToEmptyStringRector::class,
        ExplicitNullableParamTypeRector::class,
        RemoveGetClassGetParentClassNoArgsRector::class,
        SleepToSerializeRector::class,
    ])
    ->withPhpVersion(PhpVersion::PHP_81)
    ->withEagerlyResolvedDeprecations(false);
