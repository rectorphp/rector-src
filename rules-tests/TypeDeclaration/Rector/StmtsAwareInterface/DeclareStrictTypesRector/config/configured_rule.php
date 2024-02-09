<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;

return RectorConfig::configure()->withSkip([
    DeclareStrictTypesRector::class => [
        // .php.inc changed .php during running test
        realpath(__DIR__ . '/../Fixture') . '/skipped_by_path.php',
    ],
])->withRules([DeclareStrictTypesRector::class]);
