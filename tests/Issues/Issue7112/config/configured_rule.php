<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;

return RectorConfig::configure()
    ->withRules(
        [RenameVariableToMatchMethodCallReturnTypeRector::class, AddVoidReturnTypeWhereNoReturnRector::class]
    );
