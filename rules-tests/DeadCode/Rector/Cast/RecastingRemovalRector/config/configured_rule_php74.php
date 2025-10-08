<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Cast\RecastingRemovalRector;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withRules([RecastingRemovalRector::class])
    ->withPhpVersion(PhpVersion::PHP_74);
