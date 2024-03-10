<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php72\Rector\FuncCall\ParseStrWithResultArgumentRector;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\DeclareStrictTypesRector;

return RectorConfig::configure()
    ->withRules([DeclareStrictTypesRector::class, ParseStrWithResultArgumentRector::class]);
