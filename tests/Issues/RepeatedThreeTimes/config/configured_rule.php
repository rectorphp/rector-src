<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Tests\Issues\RepeatedThreeTimes\Source\NarrowArrayCollectionDocblockRector;
use Rector\Tests\Issues\RepeatedThreeTimes\Source\NarrowParamUnionDocblockRector;
use Rector\Tests\Issues\RepeatedThreeTimes\Source\ParamTypeFromDocblockRector;

return RectorConfig::configure()
    ->withImportNames()
    // registered in reverse order of dependency:
    // 3rd pass: native param type from @param SomeCollection
    // 2nd pass: @param array|SomeCollection to @param SomeCollection
    // 1st pass: @param array|SomeArrayCollection to @param array|SomeCollection
    ->withRules([
        ParamTypeFromDocblockRector::class,
        NarrowParamUnionDocblockRector::class,
        NarrowArrayCollectionDocblockRector::class,
    ]);
