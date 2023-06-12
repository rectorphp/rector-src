<?php

declare(strict_types=1);

use Rector\CodingStyle\Enum\PreferenceSelfThis;
use Rector\CodingStyle\Rector\ClassMethod\ReturnArrayClassMethodToYieldRector;
use Rector\CodingStyle\Rector\MethodCall\PreferThisOrSelfMethodCallRector;
use Rector\CodingStyle\Rector\String_\UseClassKeywordForClassNameResolutionRector;
use Rector\CodingStyle\ValueObject\ReturnArrayClassMethodToYield;
use Rector\Config\RectorConfig;
use Rector\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector;
use Rector\Naming\Rector\Class_\RenamePropertyToMatchTypeRector;
use Rector\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
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
        SetList::STRICT_BOOLEANS,
    ]);

    $rectorConfig->rules([DeclareStrictTypesRector::class]);

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
        RenamePropertyToMatchTypeRector::class => [
            __DIR__ . '/src/Console/Command/ListRulesCommand.php',
            __DIR__ . '/src/Configuration/ConfigInitializer.php',
        ],

        // resolve later
        RenameParamToMatchTypeRector::class => [
            __DIR__ . '/src/Console/Command/ListRulesCommand.php',
            __DIR__ . '/src/Configuration/ConfigInitializer.php',
            __DIR__ . '/src/PhpParser/NodeTraverser/RectorNodeTraverser.php',
        ],

        RenameVariableToMatchMethodCallReturnTypeRector::class => [
            __DIR__ . '/packages/Config/RectorConfig.php',
        ],

        StringClassNameToClassConstantRector::class,
        __DIR__ . '/bin/validate-phpstan-version.php',
        // tests
        '**/Fixture*',
        '**/Source*',
        '**/Expected*',

        // keep configs untouched, as the classes are just strings
        UseClassKeywordForClassNameResolutionRector::class => [__DIR__ . '/config', '*/config/*'],

        // avoid simplifying itself
        \Rector\CodeQuality\Rector\FuncCall\SimplifyRegexPatternRector::class => [
            __DIR__ . '/rules/CodeQuality/Rector/FuncCall/SimplifyRegexPatternRector.php',
        ],

        // race condition with stmts aware patch and PHPStan type
        \Rector\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector::class => [
            __DIR__ . '/rules/DeadCode/Rector/If_/RemoveUnusedNonEmptyArrayBeforeForeachRector.php',
        ],

        \Rector\Strict\Rector\If_\BooleanInIfConditionRuleFixerRector::class => [
            __DIR__ . '/src/DependencyInjection',
        ],
        \Rector\Strict\Rector\Ternary\DisallowedShortTernaryRuleFixerRector::class => [
            __DIR__ . '/src/DependencyInjection',
        ],

        \Rector\DeadCode\Rector\ConstFetch\RemovePhpVersionIdCheckRector::class => [
            __DIR__ . '/src/Util/FileHasher.php',
        ],
    ]);

    $rectorConfig->phpstanConfig(__DIR__ . '/phpstan-for-rector.neon');
};
