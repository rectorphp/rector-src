<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\IncreaseDeclareStrictTypesRector;

return RectorConfig::configure()
    ->withConfiguredRule(IncreaseDeclareStrictTypesRector::class, [
        IncreaseDeclareStrictTypesRector::LIMIT => 0,
    ]);
