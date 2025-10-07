<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Casing\LowercaseKeywordsFixer;
use PhpCsFixer\Fixer\Phpdoc\GeneralPhpdocAnnotationRemoveFixer;
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

        // avoid re-running on build
        __DIR__ . '/preload.php',
        __DIR__ . '/preload-split-package.php',

        PhpdocTypesFixer::class => [
            // double to Double false positive
            __DIR__ . '/rules/Php74/Rector/Double/RealToFloatTypeCastRector.php',
            // Scalar to scalar false positive
            __DIR__ . '/src/NodeTypeResolver/NodeTypeResolver/ScalarTypeResolver.php',
        ],

        GeneralPhpdocAnnotationRemoveFixer::class => [
            // bug remove @author annotation
            __DIR__ . '/src/Util/ArrayParametersMerger.php',
        ],

        LowercaseKeywordsFixer::class => [__DIR__ . '/src/ValueObject/Visibility.php'],
    ])
    ->withRootFiles();
