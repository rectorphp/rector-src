<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FuncCall\BoolvalToTypeCastRector;
use Rector\CodeQuality\Rector\FuncCall\FloatvalToTypeCastRector;
use Rector\CodeQuality\Rector\FuncCall\IntvalToTypeCastRector;
use Rector\CodeQuality\Rector\FuncCall\StrvalToTypeCastRector;
use Rector\CodingStyle\Rector\ArrowFunction\StaticArrowFunctionRector;
use Rector\CodingStyle\Rector\Closure\StaticClosureRector;
use Rector\CodingStyle\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector;
use Rector\CodingStyle\Rector\Plus\UseIncrementAssignRector;
use Rector\CodingStyle\Rector\PostInc\PostIncDecToPreIncDecRector;
use Rector\CodingStyle\Rector\String_\UseClassKeywordForClassNameResolutionRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ConstFetch\RemovePhpVersionIdCheckRector;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Privatization\Rector\Class_\FinalizeTestCaseClassRector;
use Rector\TypeDeclaration\Rector\BooleanAnd\BinaryOpNullableToInstanceofRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\IncreaseDeclareStrictTypesRector;
use Rector\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector;

return RectorConfig::configure()
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        privatization: true,
        strictBooleans: true,
        instanceOf: true,
        earlyReturn: true,
        naming: true
    )
    ->withPhpSets()
    ->withRules([
        DeclareStrictTypesRector::class,
        BinaryOpNullableToInstanceofRector::class,
        WhileNullableToInstanceofRector::class,
        IntvalToTypeCastRector::class,
        StrvalToTypeCastRector::class,
        BoolvalToTypeCastRector::class,
        FloatvalToTypeCastRector::class,
        IncreaseDeclareStrictTypesRector::class,
        StaticClosureRector::class,
        StaticArrowFunctionRector::class,
        PostIncDecToPreIncDecRector::class,
        UseIncrementAssignRector::class,
        ArraySpreadInsteadOfArrayMergeRector::class,
        FinalizeTestCaseClassRector::class,
    ])
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
    ->withRootFiles()
    ->withImportNames(removeUnusedImports: true)
    ->withSkip([
        StringClassNameToClassConstantRector::class,
        __DIR__ . '/bin/validate-phpstan-version.php',
        // tests
        '*/Fixture/*',
        '*/Fixture*',
        '*/Source/*',
        '*/Source*',
        '*/Expected/*',

        // keep configs untouched, as the classes are just strings
        UseClassKeywordForClassNameResolutionRector::class => [__DIR__ . '/config', '*/config/*'],

        // race condition with stmts aware patch and PHPStan type
        AddMethodCallBasedStrictParamTypeRector::class => [
            __DIR__ . '/rules/DeadCode/Rector/If_/RemoveUnusedNonEmptyArrayBeforeForeachRector.php',
        ],

        RemovePhpVersionIdCheckRector::class => [__DIR__ . '/src/Util/FileHasher.php'],
    ]);
