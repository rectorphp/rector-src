<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Node\RemoveNonExistingVarAnnotationRector;
use Rector\TypeDeclaration\Rector\Expression\InlineVarDocTagToAssertRector;

return RectorConfig::configure()
    ->withRules([
        InlineVarDocTagToAssertRector::class,
        RemoveNonExistingVarAnnotationRector::class,
    ]);
