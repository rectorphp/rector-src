<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FuncCall\SimplifyRegexPatternRector;
use Rector\CodingStyle\Rector\String_\UseClassKeywordForClassNameResolutionRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ConstFetch\RemovePhpVersionIdCheckRector;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;

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
    // @todo improve
    ->withSets([LevelSetList::UP_TO_PHP_82])
    ->withRules([DeclareStrictTypesRector::class])
    ->withPaths([
        __DIR__ . '/bin',
        __DIR__ . '/src',
        __DIR__ . '/rules',
        __DIR__ . '/rules-tests',
        __DIR__ . '/tests',
        __DIR__ . '/utils',
        __DIR__ . '/config',
    ])
    ->withRootFiles()
    ->withImportNames()
    ->withRemoveUnusedImports()
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

        // avoid simplifying itself
        SimplifyRegexPatternRector::class => [
            __DIR__ . '/rules/CodeQuality/Rector/FuncCall/SimplifyRegexPatternRector.php',
        ],

        // race condition with stmts aware patch and PHPStan type
        AddMethodCallBasedStrictParamTypeRector::class => [
            __DIR__ . '/rules/DeadCode/Rector/If_/RemoveUnusedNonEmptyArrayBeforeForeachRector.php',
        ],

        RemovePhpVersionIdCheckRector::class => [__DIR__ . '/src/Util/FileHasher.php'],
    ]);
