<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php84\Rector\Foreach_\ForeachToArrayFindKeyRector;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withRules([ForeachToArrayFindKeyRector::class])
    ->withPhpVersion(PhpVersion::PHP_84);
