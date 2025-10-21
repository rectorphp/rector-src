<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\String_\UseClassKeywordForClassNameResolutionRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ConstFetch\RemovePhpVersionIdCheckRector;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;

return RectorConfig::configure()
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        typeDeclarationDocblocks: true,
        privatization: true,
        naming: true,
        instanceOf: true,
        earlyReturn: true,
        rectorPreset: true,
        phpunitCodeQuality: true
    )
    ->withAttributesSets()
    ->withComposerBased(phpunit: true)
    ->withPhpSets()
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
        // tests
        '*/Fixture*',
        '*/Source*',
        '*/Expected/*',

        // keep configs untouched, as the classes are just strings
        UseClassKeywordForClassNameResolutionRector::class => [__DIR__ . '/config', '*/config/*'],

        RemovePhpVersionIdCheckRector::class => [
            __DIR__ . '/src/Util/FileHasher.php',
            __DIR__ . '/src/Configuration/RectorConfigBuilder.php',
            __DIR__ . '/src/Console/Notifier.php',
        ],
    ]);
