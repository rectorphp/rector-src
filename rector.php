<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Rector\CodingStyle\Enum\PreferenceSelfThis;
use Rector\CodingStyle\Rector\ClassMethod\ReturnArrayClassMethodToYieldRector;
use Rector\CodingStyle\Rector\MethodCall\PreferThisOrSelfMethodCallRector;
use Rector\CodingStyle\Rector\String_\SplitStringClassConstantToClassConstFetchRector;
use Rector\CodingStyle\ValueObject\ReturnArrayClassMethodToYield;
use Rector\Core\Configuration\Option;
use Rector\Nette\Set\NetteSetList;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

return static function (ContainerConfigurator $containerConfigurator): void {
    // include the latest PHP version + all bellow in one config!
    $containerConfigurator->import(LevelSetList::UP_TO_PHP_80);

    // include sets
    $containerConfigurator->import(SetList::CODING_STYLE);
    $containerConfigurator->import(SetList::CODING_STYLE_ADVANCED);
    $containerConfigurator->import(SetList::CODE_QUALITY);
    $containerConfigurator->import(SetList::DEAD_CODE);
    $containerConfigurator->import(SetList::PRIVATIZATION);
    $containerConfigurator->import(SetList::NAMING);
    $containerConfigurator->import(SetList::TYPE_DECLARATION);
    $containerConfigurator->import(SetList::EARLY_RETURN);
    $containerConfigurator->import(SetList::TYPE_DECLARATION_STRICT);
    $containerConfigurator->import(NetteSetList::NETTE_UTILS_CODE_QUALITY);
    $containerConfigurator->import(PHPUnitSetList::PHPUNIT_CODE_QUALITY);

    $services = $containerConfigurator->services();

    // phpunit
    $services->set(PreferThisOrSelfMethodCallRector::class)
        ->call('configure', [[
            PreferThisOrSelfMethodCallRector::TYPE_TO_PREFERENCE => [
                TestCase::class => ValueObjectInliner::inline(PreferenceSelfThis::PREFER_THIS()),
            ],
        ]]);

    $services->set(ReturnArrayClassMethodToYieldRector::class)
        ->call('configure', [[
            ReturnArrayClassMethodToYieldRector::METHODS_TO_YIELDS => ValueObjectInliner::inline([
                new ReturnArrayClassMethodToYield('PHPUnit\Framework\TestCase', '*provide*'),
            ]),
        ]]);

    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::PATHS, [
        __DIR__ . '/src',
        __DIR__ . '/rules',
        __DIR__ . '/rules-tests',
        __DIR__ . '/packages',
        __DIR__ . '/packages-tests',
        __DIR__ . '/tests',
        __DIR__ . '/utils',
        __DIR__ . '/config',
        __DIR__ . '/scoper.php',
    ]);

    $parameters->set(Option::AUTO_IMPORT_NAMES, true);

    $parameters->set(Option::SKIP, [
        StringClassNameToClassConstantRector::class,
        // some classes in config might not exist without dev dependencies
        SplitStringClassConstantToClassConstFetchRector::class,

        // test paths
        '*/tests/**/Fixture/*',
        '*/rules-tests/**/Fixture/*',
        '*/packages-tests/**/Fixture/*',
        '*/tests/**/Fixture*/*',
        '*/rules-tests/**/Fixture*/*',
        '*/packages-tests/**/Fixture*/*',
        // source
        '*/tests/**/Source/*',
        '*/rules-tests/**/Source/*',
        '*/packages-tests/**/Source/*',
        '*/tests/**/Source*/*',
        '*/rules-tests/**/Source*/*',
        '*/packages-tests/**/Source*/*',
        '*/tests/**/Expected/*',
        '*/rules-tests/**/Expected/*',
        '*/packages-tests/**/Expected/*',
        '*/tests/**/Expected*/*',
        '*/rules-tests/**/Expected*/*',
        '*/packages-tests/**/Expected*/*',

        // to keep original API from PHPStan untouched
        __DIR__ . '/packages/Caching/ValueObject/Storage/FileCacheStorage.php',
    ]);

    $parameters->set(Option::PHPSTAN_FOR_RECTOR_PATH, __DIR__ . '/phpstan-for-rector.neon');
};
