<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Tests\Issues\ImportFullyQualifiedIdentifierDocblock\Source\AddFullyQualifiedIdentifierDocblockRector;

return RectorConfig::configure()
    ->withRules([
        AddFullyQualifiedIdentifierDocblockRector::class,
    ])
    ->withImportNames();
