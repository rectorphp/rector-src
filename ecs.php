<?php

declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPreparedSets(symplify: true, common: true, psr12: true)
    ->withPaths([
        __DIR__ . '/bin',
        __DIR__ . '/src',
        __DIR__ . '/rules',
        __DIR__ . '/rules-tests',
        __DIR__ . '/tests',
        __DIR__ . '/utils',
        __DIR__ . '/config',
        __DIR__ . '/build/build-preload.php',
    ])
    ->withRootFiles();

//
//    $ecsConfig->skip([
//        '*/Source/*',
//        '*/Fixture/*',
//        '*/Expected/*',
//
//        PhpdocTypesFixer::class => [
//            // double to Double false positive
//            __DIR__ . '/rules/Php74/Rector/Double/RealToFloatTypeCastRector.php',
//            // skip for enum types
//            __DIR__ . '/rules/Php70/Rector/MethodCall/ThisCallOnStaticMethodToStaticCallRector.php',
//        ],
//
//        SelfAccessorFixer::class => ['*/*Rector.php'],
//    ]);
