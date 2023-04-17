<?php

declare(strict_types=1);

use Rector\CodingStyle\Enum\PreferenceSelfThis;
use Rector\CodingStyle\Rector\ClassMethod\ReturnArrayClassMethodToYieldRector;
use Rector\CodingStyle\Rector\MethodCall\PreferThisOrSelfMethodCallRector;
use Rector\CodingStyle\Rector\String_\UseClassKeywordForClassNameResolutionRector;
use Rector\CodingStyle\ValueObject\ReturnArrayClassMethodToYield;
use Rector\Config\RectorConfig;
use Rector\Naming\Rector\Foreach_\RenameForeachValueVariableToMatchExprVariableRector;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\ClassMethod\FalseReturnClassMethodToNullableRector;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_81,
        PHPUnitSetList::PHPUNIT_100,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
        SetList::PRIVATIZATION,
        SetList::NAMING,
        SetList::TYPE_DECLARATION,
        SetList::INSTANCEOF,
        SetList::EARLY_RETURN,
        PHPUnitSetList::PHPUNIT_CODE_QUALITY,
        SetList::CODING_STYLE,
    ]);

    $rectorConfig->rules([FalseReturnClassMethodToNullableRector::class, DeclareStrictTypesRector::class]);

    $rectorConfig->ruleWithConfiguration(
        PreferThisOrSelfMethodCallRector::class,
        [
            'PHPUnit\Framework\TestCase' => PreferenceSelfThis::PREFER_THIS,
        ]
    );

    $rectorConfig->ruleWithConfiguration(ReturnArrayClassMethodToYieldRector::class, [
        new ReturnArrayClassMethodToYield('PHPUnit\Framework\TestCase', '*provide*'),
    ]);

    $rectorConfig->paths([
        __DIR__ . '/bin',
        __DIR__ . '/src',
        __DIR__ . '/rules',
        __DIR__ . '/rules-tests',
        __DIR__ . '/packages',
        __DIR__ . '/packages-tests',
        __DIR__ . '/tests',
        __DIR__ . '/utils',
        __DIR__ . '/config',
        __DIR__ . '/scoper.php',
        __DIR__ . '/build/build-preload.php',
    ]);

    $rectorConfig->importNames();
    $rectorConfig->removeUnusedImports();
    $rectorConfig->parallel();

    $rectorConfig->skip([
        StringClassNameToClassConstantRector::class,

        // tests
        '**/Fixture*',
        '**/Fixture/*',
        '**/Source*',
        '**/Source/*',
        '**/Expected/*',
        '**/Expected*',
        __DIR__ . '/tests/PhpUnit/MultipleFilesChangedTrait/MultipleFilesChangedTraitTest.php',

        // to keep original API from PHPStan untouched
        __DIR__ . '/packages/Caching/ValueObject/Storage/FileCacheStorage.php',

        // keep configs untouched, as the classes are just strings
        UseClassKeywordForClassNameResolutionRector::class => [__DIR__ . '/config', '*/config/*'],
        RenameForeachValueVariableToMatchExprVariableRector::class => [
            // false positive on plurals
            __DIR__ . '/packages/Testing/PHPUnit/Behavior/MovingFilesTrait.php',
        ],

        // race condition with stmts aware patch and PHPStan type
        \Rector\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector::class => [
            __DIR__ . '/rules/DeadCode/Rector/If_/RemoveUnusedNonEmptyArrayBeforeForeachRector.php',
        ],
    ]);

    $rectorConfig->phpstanConfig(__DIR__ . '/phpstan-for-rector.neon');
};
