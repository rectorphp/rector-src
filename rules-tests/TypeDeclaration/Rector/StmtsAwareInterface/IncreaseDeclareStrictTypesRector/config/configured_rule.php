<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\IncreaseDeclareStrictTypesRector;

return RectorConfig::configure()
    ->withRules([IncreaseDeclareStrictTypesRector::class]);
