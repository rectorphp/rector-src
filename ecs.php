<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ClassNotation\SelfAccessorFixer;
use PhpCsFixer\Fixer\FunctionNotation\FunctionTypehintSpaceFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocTypesFixer;
use PhpCsFixer\Fixer\Whitespace\MethodChainingIndentationFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->sets([SetList::SYMPLIFY, SetList::COMMON, SetList::CLEAN_CODE, SetList::PSR_12]);

    $ecsConfig->paths([
        __DIR__ . '/bin',
        __DIR__ . '/src',
        __DIR__ . '/packages',
        __DIR__ . '/packages-tests',
        __DIR__ . '/rules',
        __DIR__ . '/rules-tests',
        __DIR__ . '/tests',
        __DIR__ . '/utils',
        __DIR__ . '/utils-tests',
        __DIR__ . '/config',
        __DIR__ . '/ecs.php',
        __DIR__ . '/rector.php',
        __DIR__ . '/build/build-preload.php',
    ]);

    $ecsConfig->rules([FunctionTypehintSpaceFixer::class]);

    $ecsConfig->skip([
        '*/Source/*',
        '*/Fixture/*',
        '*/Expected/*',

        MethodChainingIndentationFixer::class => [
            // nested indents
            __DIR__ . '/rector.php',
        ],

        PhpdocTypesFixer::class => [
            // double to Double false positive
            __DIR__ . '/rules/Php74/Rector/Double/RealToFloatTypeCastRector.php',
            // skip for enum types
            __DIR__ . '/rules/Php70/Rector/MethodCall/ThisCallOnStaticMethodToStaticCallRector.php',
        ],

        SelfAccessorFixer::class => ['*/*Rector.php'],
    ]);
};
