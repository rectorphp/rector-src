<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector;

return RectorConfig::configure()
    ->withRules([AddVoidReturnTypeWhereNoReturnRector::class]);
