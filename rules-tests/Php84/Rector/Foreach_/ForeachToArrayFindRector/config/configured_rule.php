<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php84\Rector\Foreach_\ForeachToArrayFindRector;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withRules([ForeachToArrayFindRector::class])
    ->withPhpVersion(PhpVersion::PHP_84);
