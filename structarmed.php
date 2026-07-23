<?php

declare(strict_types=1);

use Boundwize\StructArmed\Architecture;
use Boundwize\StructArmed\Preset\Preset;
use Boundwize\StructArmed\Preset\Presets\Psr4Preset;
use Boundwize\StructArmed\Rule\Rules\Class_\MustBeFinalRule;

return Architecture::define()
    ->rule(
        'source.must_be_final',
        new MustBeFinalRule(layer: 'Source'),
    )
    ->skip([
        Psr4Preset::CLASSES_MUST_MATCH_COMPOSER => [
            // the namespace different is on purpose
            __DIR__ . '/rules-tests/Renaming/Rector/Name/RenameClassRector',
            __DIR__ . '/rules-tests/CodingStyle/Rector/Namespace_',

            // no namespace on purpose
            __DIR__ . '/rules-tests/Php70/Rector/ClassMethod/Php4ConstructorRector/Source/ParentClass.php',

            // simulate under phpstan.phar
            __DIR__ . '/rules-tests/Php71/Rector/FuncCall/RemoveExtraParametersRector/Source/phpstan.phar',
        ],
        'source.must_be_final' => [
            '*/Source/*',
            '*/Fixture*',
        ],
    ])
    ->withPreset(Preset::PSR4());
