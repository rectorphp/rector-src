<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Phpdoc\PhpdocTypesFixer;
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
    ->withSkip([
        '*/Source/*',
        '*/Fixture/*',
        '*/Expected/*',

        PhpdocTypesFixer::class => [
            // double to Double false positive
            __DIR__ . '/rules/Php74/Rector/Double/RealToFloatTypeCastRector.php',
        ],
    ])
    ->withRootFiles();
