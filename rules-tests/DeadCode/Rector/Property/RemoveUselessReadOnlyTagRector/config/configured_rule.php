<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Property\RemoveUselessReadOnlyTagRector;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withRules([RemoveUselessReadOnlyTagRector::class])
    ->withPhpVersion(PhpVersion::PHP_81);
