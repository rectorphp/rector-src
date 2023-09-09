<?php

declare(strict_types=1);

use PHP_CodeSniffer\Standards\Generic\Sniffs\CodeAnalysis\AssignmentInConditionSniff;
use PhpCsFixer\Fixer\Phpdoc\NoSuperfluousPhpdocTagsFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocNoEmptyReturnFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocTypesFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitStrictFixer;
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

    $ecsConfig->rules([\PhpCsFixer\Fixer\FunctionNotation\FunctionTypehintSpaceFixer::class]);

    $ecsConfig->ruleWithConfiguration(NoSuperfluousPhpdocTagsFixer::class, [
        'allow_mixed' => true,
    ]);

    $ecsConfig->skip([
        '*/Source/*',
        '*/Fixture/*',
        '*/Expected/*',

        PhpdocTypesFixer::class => [
            // double to Double false positive
            __DIR__ . '/rules/Php74/Rector/Double/RealToFloatTypeCastRector.php',
            // skip for enum types
            __DIR__ . '/rules/Php70/Rector/MethodCall/ThisCallOnStaticMethodToStaticCallRector.php',
        ],

        // breaking and handled better by Rector PHPUnit code quality set, removed in symplify dev-main
        PhpUnitStrictFixer::class,

        AssignmentInConditionSniff::class . '.FoundInWhileCondition',

        // null on purpose as no change
        PhpdocNoEmptyReturnFixer::class => [
            __DIR__ . '/rules/DeadCode/Rector/ConstFetch/RemovePhpVersionIdCheckRector.php',
        ],
    ]);
};
