<?php

declare(strict_types=1);

use Boundwize\StructArmed\Architecture;
use Boundwize\StructArmed\Preset\Preset;
use Boundwize\StructArmed\Preset\Presets\Psr4Preset;

return Architecture::define()
    ->skip([
        Psr4Preset::CLASSES_MUST_MATCH_COMPOSER => [
            // the namespace different is on purpose
            __DIR__ . '/rules-tests/Renaming/Rector/Name/RenameClassRector',
            __DIR__ . '/rules-tests/CodingStyle/Rector/Namespace_',

            // no namespace on purpose
            __DIR__ . '/rules-tests/Php70/Rector/ClassMethod/Php4ConstructorRector/Source/ParentClass.php',

            // multi classes in one file on purpose
            __DIR__ . '/rules-tests/Php70/Rector/StaticCall/StaticCallOnNonStaticToInstanceCallRector/Source/Service.php',

            // simulate under phpstan.phar
            __DIR__ . '/rules-tests/Php71/Rector/FuncCall/RemoveExtraParametersRector/Source/phpstan.phar',
        ],
    ])
    ->withPreset(Preset::PSR4());
