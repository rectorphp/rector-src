<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedConstructorParamRector;
use Rector\Tests\Issues\ImportFullyQualifiedIdentifierDocblock\Source\AddFullyQualifiedIdentifierDocblockRector;

return RectorConfig::configure()
    ->withRules([AddFullyQualifiedIdentifierDocblockRector::class, RemoveUnusedConstructorParamRector::class])
    ->withImportNames()
    ->withSkip([
        RemoveUnusedConstructorParamRector::class => [
            realpath(__DIR__ . '/../Fixture') . '/keep_import_used_in_param.php',
        ],
    ]);
