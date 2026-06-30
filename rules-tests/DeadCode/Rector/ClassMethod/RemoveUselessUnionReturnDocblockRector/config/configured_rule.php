<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessUnionReturnDocblockRector;
use Rector\ValueObject\PhpVersionFeature;

return RectorConfig::configure()
    ->withPhpVersion(PhpVersionFeature::UNION_TYPES)
    ->withRules([RemoveUselessUnionReturnDocblockRector::class]);
