<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\FunctionNotation\FunctionTypehintSpaceFixer;
use PhpCsFixer\Fixer\Phpdoc\GeneralPhpdocAnnotationRemoveFixer;
use PhpCsFixer\Fixer\Phpdoc\NoSuperfluousPhpdocTagsFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocTypesFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitStrictFixer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\CodingStandard\Fixer\LineLength\DocBlockLineLengthFixer;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(NoSuperfluousPhpdocTagsFixer::class)
        ->call('configure', [[
            'allow_mixed' => true,
        ]]);

    $services->set(GeneralPhpdocAnnotationRemoveFixer::class)
        ->call('configure', [[
            'annotations' => [
                'throw',
                'throws',
                'author',
                'authors',
                'package',
                'group',
                'required',
                'phpstan-ignore-line',
                'phpstan-ignore-next-line',
            ],
        ]]);

    $parameters = $containerConfigurator->parameters();

    // bleeding edge 16x faster speed
    $parameters->set(Option::PARALLEL, true);

    $parameters->set(Option::PATHS, [
        __DIR__ . '/bin',
        __DIR__ . '/src',
        __DIR__ . '/packages',
        __DIR__ . '/packages-tests',
        __DIR__ . '/rules',
        __DIR__ . '/rules-tests',
        __DIR__ . '/tests',
        __DIR__ . '/utils',
        __DIR__ . '/config',
        __DIR__ . '/ecs.php',
        __DIR__ . '/easy-ci.php',
        __DIR__ . '/rector.php',
        __DIR__ . '/scoper.php',
    ]);

    $parameters->set(Option::SKIP, [
        '*/Source/*',
        '*/Fixture/*',
        '*/Expected/*',

        // buggy - @todo fix on Symplify master
        DocBlockLineLengthFixer::class,

        // double to Double false positive
        PhpdocTypesFixer::class => [__DIR__ . '/rules/Php74/Rector/Double/RealToFloatTypeCastRector.php'],

        // breaking and handled better by Rector PHPUnit code quality set, removed in symplify dev-main
        PhpUnitStrictFixer::class,

        // skip add space on &$variable
        FunctionTypehintSpaceFixer::class => [
            __DIR__ . '/src/PhpParser/Printer/BetterStandardPrinter.php',
            __DIR__ . '/src/DependencyInjection/Loader/Configurator/RectorServiceConfigurator.php',
            __DIR__ . '/rules/Php70/EregToPcreTransformer.php',
        ],
    ]);

    // import SetList here in the end of ecs. is on purpose
    // to avoid overridden by existing Skip Option in current config
    $containerConfigurator->import(SetList::PSR_12);
    $containerConfigurator->import(SetList::SYMPLIFY);
    $containerConfigurator->import(SetList::COMMON);
    $containerConfigurator->import(SetList::CLEAN_CODE);

    $parameters->set(Option::LINE_ENDING, "\n");
};
